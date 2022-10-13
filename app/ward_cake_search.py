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
def get_patisserie_list(region, cities, term):
  csv = [['shop_name', 'url', 'place', 'types', 'star', 'price_dinner', 'price_lunch', 'holiday', 'city']]
  
  query = '/?sw=' + term
  list_type = '/rstLst/'


  for city_name, city in cities.items():
    base_url = 'https://tabelog.com/' + region + '/' + city + list_type
    page = 1
    url = base_url + str(page) + query
    soup = get_parsed_html(url)
    shops = int(soup.select('.c-page-count__num strong')[2].text)
    pages = math.ceil(shops / 20)
    cards = soup.select('div.list-rst__body')[0:shops if shops < 20 else 20]
    not_found_flg = soup.select('p.keyword-notfound')
    print(f'{city_name}: {shops} shops, {pages} pages')

    for i in range(pages):
      if i >= 1:
        url = base_url + str(i+1) + query
        soup = get_parsed_html(url)
        cards = soup.select('div.list-rst__body')

        # print('cards:', cards)


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
          city_name
        ]

        csv.append(l)
      
      print(i+1)
      time.sleep(5)

  # urlと区だけを抽出 全ての結果が欲しければ、この行はコメントアウト
  csv = map(lambda x: [x[1], x[8]], csv) 
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
      url, city_name = url.strip().split(',')
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
        city_name,
      ]
      # ,を消去
      l = list(map(lambda x: x.replace(',', ''), l))
      print(f'{len(csv)}: {l[0]}, {l[1]}, {l[9]}, {l[10]}, {l[11]}, {l[12]}')
      csv.append(l)

      time.sleep(5)
  f.close()

  f_name = 'patisseries2_' + region + '.csv'
  create_csv(f_name, csv)

if __name__ == "__main__":
  # prefecture = 'tokyo'
  # cities = {
  #   '千代田区' : 'C13101', 
  #   '中央区' : 'C13102',
  #   '港区' : 'C13103',
  #   '新宿区' : 'C13104',
  #   '文京区' : 'C13105',
  #   '台東区' : 'C13106',
  #   '墨田区' : 'C13107',
  #   '江東区' : 'C13108',
  #   '品川区' : 'C13109',
  #   '目黒区' : 'C13110',
  #   '大田区' : 'C13111',
  #   '世田谷区' : 'C13112',
  #   '渋谷区' : 'C13113',
  #   '中野区' : 'C13114',
  #   '杉並区' : 'C13115',
  #   '豊島区' : 'C13116',
  #   '北区' : 'C13117',
  #   '荒川区' : 'C13118',
  #   '板橋区' : 'C13119',
  #   '練馬区' : 'C13120',
  #   '足立区' : 'C13121',
  #   '葛飾区' : 'C13122',
  #   '江戸川区' : 'C13123',
  # }

  prefecture = "chiba"
  cities = {
    "流山市": "C12220", 
    "柏市": "C12217",
    "松戸市": "C12207",
    "市川市": "C12203",
    "浦安市": "C12227",
    "船橋市": "C12204",
    "鎌ヶ谷市" : "C12224",
    "習志野市": "C12216",
    "千葉市": "C12100",
    "八千代市": "C12221",
    "市原市": "C12219",
  }
  term = '洋菓子'

  get_patisserie_list(prefecture, cities, term)
  get_individuals(prefecture)
