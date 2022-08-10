import requests
from bs4 import BeautifulSoup

import math
import time

# params: url
# return: soup
def get_parsed_html(url):
  url = url.strip()
  try:
    html = requests.get(url).text
    return BeautifulSoup(html, 'html.parser')
  except:
    print('error: ' + url)
    return False

# params: csv用のlist
# return: void
def create_csv(f_name, list):
  with open(f_name, 'w', encoding='utf_8_sig') as f:
    for l in list:
      print(l)
      f.write(','.join(l) + '\n')
  f.close()

# 食べログの検索結果一覧からURLを取得する
# params: void
# return: void
def get_patisserie_list(region):
  csv = [['shop_name', 'url', 'place', 'types', 'star', 'price_dinner', 'price_lunch', 'holiday']]
  
  list_type = '/rstLst/'
  base_url = 'https://tabelog.com/' + region + list_type
  page = 1
  query = '/?sw=パティスリー'

  url = base_url + str(page) + query

  soup = get_parsed_html(url)
  cards = soup.select('div.list-rst__body')
  shops = int(soup.select('.c-page-count__num strong')[2].text)
  pages = math.ceil(shops / 20)

  for i in range(pages):
    if i >= 1:
      url = base_url + str(i+1) + query
      soup = get_parsed_html(url)
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

  # urlだけを抽出 全ての結果が欲しければ、この行はコメントアウト
  csv = map(lambda x: [x[1]], csv) 
  f_name = 'patisseries_url.csv'
  create_csv(f_name, csv)

# urlのcsvから食べログの詳細ページ情報を取得する
# params: void
# return: void
def get_individuals(region):
  def _get_one(soup):
    def _get_one_element(css):
      el = soup.select_one(css)
      # print(el)
      el = el.text.strip() if el else '-'
      return el
    return _get_one_element

  def _get_many(soup):
    def _get_many_element(css):
      els = soup.select(css)
      # print(els)
      els = [el.text.strip() for el in els]
      return els
    return _get_many_element
  
  csv = [[
    'name',
    'subname',
    'url',
    'holiday',
    'nearest',
    'genre',
    'price_dinner',
    'price_lunch',
    'star',
    'address',
    'tel',
    'reviews',
    'bookmarks',
    'menu_url',
    'photo_out',
    'photo_in',
  ]]

  with open('patisseries_url.csv', 'r', encoding='utf_8_sig') as f:
    for url in f:
      url = url.strip()
      soup = get_parsed_html(url)
      if not soup: continue 

      _get_el = _get_one(soup)
      _get_els = _get_many(soup)

      name = _get_el('.display-name span')
      subname = _get_el('span.alias')
      holiday = _get_el('#short-comment')
      nearest = _get_els('.linktree__parent-target-text')[0]
      genre = ' '.join(_get_els('.linktree__parent-target-text')[2:])
      price_dinner = _get_el('.rdheader-budget__icon--dinner a')
      price_lunch = _get_el('.rdheader-budget__icon--lunch a')
      star = _get_el('.rdheader-rating__score-val-dtl')
      address = ' '.join(_get_els('.rstinfo-table__address span'))
      tel = _get_el('.rstdtl-side-yoyaku__tel-number')
      reviews = _get_el('.rdheader-rating__review-target em')
      bookmarks = _get_el('.rdheader-rating__hozon-target em')
      menu_url = url + 'dtlmenu'
      photo_out = url + 'dtlphotolst/3/smp2'
      photo_in  = url + 'dtlphotolst/4/smp2'

      l = [
        name,
        subname,
        url,
        holiday,
        nearest,
        genre,
        price_dinner,
        price_lunch,
        star,
        address,
        tel,
        reviews,
        bookmarks,
        menu_url,
        photo_out,
        photo_in,
      ]
      # ,を消去
      l = list(map(lambda x: x.replace(',', ''), l))
      print(f'{len(csv)}: {l[0]}, {l[1]}, {l[9]}, {l[10]}, {l[11]}, {l[12]}')
      csv.append(l)

      time.sleep(5)
  f.close()

  f_name = 'patisseries_' + region + '.csv'
  create_csv(f_name, csv)

if __name__ == "__main__":
  region = 'osaka'
  get_patisserie_list(region)
  get_individuals(region)
