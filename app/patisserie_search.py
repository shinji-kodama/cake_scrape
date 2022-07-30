import requests
from bs4 import BeautifulSoup

import math
import time

def test():
  csv = [['shop_name', 'url', 'place', 'types', 'star', 'price_dinner', 'price_lunch', 'holiday']]
  base_url = 'https://tabelog.com/tokyo/rstLst/'
  page = 1
  query = '/?sw=パティスリー'

  url = base_url + str(page) + query

  html = requests.get(url).text
  soup = BeautifulSoup(html, 'html.parser')
  cards = soup.select('div.list-rst__body')
  shops = int(soup.select('.c-page-count__num strong')[2].text)
  pages = math.ceil(shops / 20)

  for i in range(pages):
    if i >= 1:
      url = base_url + str(i+1) + query
      html = requests.get(url).text
      soup = BeautifulSoup(html, 'html.parser')
      cards = soup.select('div.list-rst__body')


    for card in cards:
      title = card.select_one('a.list-rst__rst-name-target')
      area_genre = card.select_one('div.list-rst__area-genre')
      star = card.select_one('span.list-rst__rating-val')
      prices = card.select('span.c-rating-v3__val')
      holiday = card.select_one('span.list-rst__holiday-text')

      (place, type) = map(lambda x: x.strip().split(' '), area_genre.text.strip().split('/'))

      dinner = prices[0].text if prices else '-'
      lunch = prices[1].text if prices else '-'
      dinner = dinner.replace(',', '') if dinner != '-' else '-'
      lunch = lunch.replace(',', '') if lunch != '-' else '-'

      l = [
        title.text.strip(),
        title.get('href'),
        (' ').join(place),
        (' ').join(type),
        star.text if star else '-',
        dinner,
        lunch,
        holiday.text if holiday else '-',
      ]

      csv.append(l)
    
    print(i+1)
    time.sleep(5)

  with open('patisseries.csv', 'w', encoding='utf_8_sig') as f:
    for l in csv:
      f.write(','.join(l) + '\n')

  # print(soup.title)


if __name__ == "__main__":
  test()
