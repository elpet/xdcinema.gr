<?php

declare(strict_types=1);

/*
 * Στατική εξαγωγή της αρχικής σελίδας (για GitHub Pages / προεπισκόπηση χωρίς PHP).
 * Χρήση: php bin/export-static.php <φάκελος-εξόδου> <δημόσιο-URL>
 * Π.χ.:  php bin/export-static.php ../xdcinema-static https://elpet.github.io/xdcinema.gr
 * Προϋπόθεση: npm run build (public/build). Η φόρμα επικοινωνίας γίνεται mailto (δεν τρέχει PHP στο Pages).
 */

$out = $argv[1] ?? null;
$url = rtrim($argv[2] ?? '', '/');
if (! $out || ! $url) {
    fwrite(STDERR, "Χρήση: php bin/export-static.php <φάκελος> <URL>\n");
    exit(1);
}

$root = dirname(__DIR__);
foreach (['APP_URL' => $url, 'ASSET_URL' => $url, 'APP_ENV' => 'production', 'APP_DEBUG' => 'false'] as $k => $v) {
    putenv("$k=$v");
    $_ENV[$k] = $v;
    $_SERVER[$k] = $v;
}

require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$html = view('home')->render();

// Φόρμα: χωρίς server, άνοιγμα email client με τα πεδία
$html = preg_replace(
    '#<form method="post" action="[^"]*"#',
    '<form method="get" action="mailto:info@xdcinema.gr"',
    $html
);
$html = preg_replace('#<input type="hidden" name="_token" value="[^"]*"[^>]*>#', '', $html);

@mkdir($out, 0777, true);
file_put_contents("$out/index.html", $html);
file_put_contents("$out/.nojekyll", '');
file_put_contents("$out/404.html", $html);

$copy = function (string $from, string $to) use (&$copy): void {
    if (is_dir($from)) {
        @mkdir($to, 0777, true);
        foreach (scandir($from) as $f) {
            if ($f !== '.' && $f !== '..') {
                $copy("$from/$f", "$to/$f");
            }
        }
    } else {
        copy($from, $to);
    }
};
$copy("$root/public/build", "$out/build");
$copy("$root/public/img", "$out/img");
copy("$root/public/favicon.svg", "$out/favicon.svg");

echo "OK: $out (".strlen($html)." bytes index.html)\n";
