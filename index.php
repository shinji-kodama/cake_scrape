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
<body>

<div class="px-4 sm:px-6 lg:px-8">
  <div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
      <h1 class="text-xl font-semibold text-gray-900">Shop Information</h1>
      <p class="mt-2 text-sm text-gray-700">A list of Patisseries searched in 食べログ</p>
    </div>
    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
      <a href="./index.php?region=tokyo">
        <button type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">東京</button>
      </a>
      <a href="./index.php?region=osaka">
        <button type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">大阪</button>
      </a>
      <?php
        if($region == 'tokyo') {
          echo '<a href="./app/patisseries_tokyo.csv">';
        } else {
          echo '<a href="./app/patisseries_osaka.csv">';
        } ?>
        <button type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">download csv</button>  
      </a>
    </div>
  </div>
  <div class="mt-8 flex flex-col">
    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-3 md:rounded-lg">
          <table class="table-fixed divide-y divide-gray-300">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">star</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">tel</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">address</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">url</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-2">holiday</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-6">near</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-6">genre</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">dinner</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">lunch</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">reviews</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">bookmarks</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">menu</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">photo_out</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">photo_in</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              <?php foreach(array_slice($data_t,1) as $i => $data) { ?>
                <tr class="<?=$i % 2 == 0 ? 'bg-slate-50' : 'bg-slate-100' ?> hover:bg-slate-200">
                  <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"><?=mb_substr($data[0].$data[1],0,64)?></td>
                  <td class="px-3 py-2 text-sm text-gray-500 truncate"><?=$data[8]?></td>
                  <td class="px-3 py-2 text-sm text-gray-500 truncate"><?=$data[10]?></td>
                  <td class="px-3 py-2 text-sm text-gray-500 truncate"><?=$data[9]?></td>
                  <td class="relative whitespace-nowrap py-2 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                    <a href="<?=data[2]?>" class="text-indigo-600 hover:text-indigo-900">link</a>
                  </td>
                  <td class="px-3 py-2 text-sm text-gray-500 w-2 truncate"><?=$data[3]?></td>
                  <td class="px-3 py-2 text-sm text-gray-500 w-2 truncate"><?=mb_substr($data[4],0,16)?></td>
                  <td class="px-3 py-2 text-sm text-gray-500 w-6 truncate"><?=mb_substr($data[5],0,16)?></td>
                  <td class="px-3 py-2 text-sm text-gray-500 truncate"><?=$data[6]?></td>
                  <td class="px-3 py-2 text-sm text-gray-500 truncate"><?=$data[7]?></td>
                  <td class="px-3 py-2 text-sm text-gray-500 truncate"><?=$data[11]?></td>
                  <td class="px-3 py-2 text-sm text-gray-500 truncate"><?=$data[12]?></td>
                  <td class="relative py-2 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                    <a href="<?=$data[13]?>" class="text-indigo-600 hover:text-indigo-900">link</a>
                  </td>
                  <td class="relative whitespace-nowrap py-2 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                    <a href="<?=$data[14]?>" class="text-indigo-600 hover:text-indigo-900">link</a>
                  </td>
                  <td class="relative whitespace-nowrap py-2 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                    <a href="<?=$data[15]?>" class="text-indigo-600 hover:text-indigo-900">link</a>
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