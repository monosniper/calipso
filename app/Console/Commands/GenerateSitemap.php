<?php

namespace App\Console\Commands;

use http\Url;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $filename = 'sitemap.xml';
            $this->path = public_path('sitemap/');

//            ini_set("memory_limit", "-1");
//            set_time_limit(0);
//            ini_set("max_execution_time", 0);
//            ignore_user_abort(true);

            if(file_exists($this->path . $filename)) {
//                chmod($this->path, 0777);
//                chmod($this->path . $filename, 0777);
                rename($this->path . $filename, $this->path . 'sitemap-old-' . date('D-d-M-Y h-s') . '.xml');
            }

            SitemapGenerator::create(config('app.url'))
                ->writeToFile($this->path . $filename);

            $sitemapUrl = config('app.url') . '/' . $filename;

//            function myCurl($url) {
//                $ch = curl_init($url);
//                curl_setopt($ch, CURLOPT_HEADER, 0);
//                curl_exec($ch);
//                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//                curl_close($ch);
//
//                return $httpCode;
//            }
//
//            $url = "https://www.google.com/webmasters/sitemaps/ping?sitemap=" . $sitemapUrl;
//            $returnCode = myCurl($url);
//            echo "<p>Google Sitemaps has been pinged (return codeL $returnCode)</p>";
        } catch (\Exception $err) {
            Log::error($err);
        }
    }
}
