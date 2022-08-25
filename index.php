<?php

$region = $_GET['region'];

$read_file_t = 'app/patisseries_tokyo.csv';
$read_file_o = 'app/patisseries_osaka.csv';

$fp_t = fopen($read_file_t, 'r');
$fp_o = fopen($read_file_o, 'r');

$data_t = [];

$f;

switch($region) {
  case 'tokyo':
    $f = $fp_t;
    break;
  case 'osaka':
    $f = $fp_o;
    break;
  default:
    $f = $fp_t;
    $region = 'tokyo';
    break;
}

while(($line = fgetcsv($f)) !== false) {
    array_push($data_t, $line);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Patisseries</title>
</head>
<body class="bg-teal-50">

<div class="mt-6 px-4 sm:px-6 lg:px-8">
  <div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
      <h1 class="text-xl font-semibold text-gray-900">Shop Information in <?=$region?></h1>
      <p class="mt-2 text-sm text-gray-700">A list of Patisseries searched in 食べログ</p>
      <p class="text-sm text-gray-60 mt-3">Update</p>
      <p class="text-xs text-gray-500 ml-3">8/25(木)：csvのダウンロードが正常に機能するようになりました</p>
    </div>
    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
      <a href="./index.php?region=tokyo">
        <button type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-teal-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 sm:w-auto">東京</button>
      </a>
      <a href="./index.php?region=osaka">
        <button type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-teal-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 sm:w-auto">大阪</button>
      </a>
      <a href="./app/patisseries_<?=region?>.csv">
        <button type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-teal-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 sm:w-auto">download csv</button>  
      </a>
    </div>
  </div>
  <div class="mt-8 flex flex-col">
    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
          <table class="table-auto divide-y divide-gray-300">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="py-1 pr-3 pl-2 text-left text-sm font-semibold text-gray-900 w-2 sm:pl-2">No.</th>
                <th scope="col" class="py-1 px-1 text-left text-sm font-semibold text-gray-900 sm:pl-2">Name</th>
                <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900 ">holiday<br>other</th>
                <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900 ">price</th>
                <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900">評価</th>
                <th scope="col" class="px-1 py-1 text-left text-sm font-semibold text-gray-900"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              <?php foreach(array_slice($data_t,1) as $i => $data) { ?>
                <tr class="<?=$i % 2 == 0 ? 'bg-slate-50' : 'bg-slate-100' ?> hover:bg-slate-200">
                  <td class="pr-2 pl-4 py-2 text-xs text-gray-500 truncate"><?=$i+1?></td>
                  <td class="px-2 py-2 text-xs text-gray-500 truncate">
                    <div class="max-w-md">
                      <p class="truncate"><?=$data[0].$data[1]?></p>
                      <p class="mt-2 truncate">
                        <a href="tel:<?=$data[10]?>">
                          TEL : <?=$data[10]?>
                        </a>, 
                        <a href="https://www.google.com/maps/search/?api=1&query=<?=$data[9]?>">
                          addr: <?=$data[9]?>
                        </a>
                      </p>
                    </div>
                  </td>
                  <td class="px-2 py-2 text-xs text-gray-500 truncate">
                    <div class="w-60">
                      <p class="truncate"><?=$data[3]?></p>
                      <p class="truncate mt-2"><?=$data[4].', '.$data[5]?></p>
                    </div>
                  </td>
                  <td class="px-2 py-2 text-xs text-gray-500 truncate">
                    <div>
                      <span class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg> : <?=$data[6]?>
                      </span>
                      <span class="flex mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg> : <?=$data[7]?>
                      </span>
                    </div>
                  </td>
                  <td class="px-2 py-2 text-xs text-gray-500 truncate">
                    <div class="flex flex-col items-start xl:flex-row xl:space-x-2 xl:items-center">
                      <span class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>: 
                        <?=$data[8]?>
                      </span>
                      <span class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>: 
                        <?=$data[11]?> 
                      </span>
                      <span class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                        </svg>: 
                        <?=$data[12]?> 
                      </span>
                    </div>
                  </td>
                  <td class="py-2 pl-3 pr-4 text-right text-xs font-medium sm:pr-6 align-middle w-full">
                    <div class="flex items-center space-x-4">
                      <span class="flex flex-col items-center space-y-2 xl:flex-row xl:space-x-2 xl:space-y-0">
                        <a href="<?=$data[2]?>" class="text-teal-600 hover:text-teal-900 truncate">TOP</a>
                        <a href="<?=$data[13]?>" class="text-teal-600 hover:text-teal-900 truncate">MENU</a>
                      </span>
                      <span class="flex flex-col items-center space-y-2 xl:flex-row xl:space-x-2 xl:space-y-0">
                        <a href="<?=$data[14]?>" class="text-teal-600 hover:text-teal-900 truncate">外観</a>
                        <a href="<?=$data[15]?>" class="text-teal-600 hover:text-teal-900 truncate">内観</a>
                      </span>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>