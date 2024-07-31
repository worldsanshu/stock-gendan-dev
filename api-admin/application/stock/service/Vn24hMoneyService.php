<?php
declare(strict_types = 1);
namespace app\stock\service;

use Symfony\Component\HttpClient\HttpClient;
use think\Db;
use app\stock\model\CmsKuaixun;

class Vn24hMoneyService
{

    protected $paramsCommon = [
        'device_id' => 'web1721875rl9h1mjk67jxyrkq5ypzehxi3gihbc5d731084',
        'device_name' => 'INVALID',
        'device_model' => 'Windows 10',
        'network_carrier' => 'INVALID',
        'connection_type' => 'INVALID',
        'os' => 'Edge',
        'os_version' => '126.0.0.0',
        'access_token' => 'INVALID',
        'push_token' => 'INVALID',
        'locale' => 'vi',
        'browser_id' => 'web1721875rl9h1mjk67jxyrkq5ypzehxi3gihbc5d731084'
    ];

    public function getKuaixun()
    {
        // https://api-finance-t19.24hmoney.vn/v1/web/announcement/dividend-events?device_id=web1721875rl9h1mjk67jxyrkq5ypzehxi3gihbc5d731084&device_name=INVALID&device_model=Windows+10&network_carrier=INVALID&connection_type=INVALID&os=Edge&os_version=126.0.0.0&access_token=INVALID&push_token=INVALID&locale=vi&browser_id=web1721875rl9h1mjk67jxyrkq5ypzehxi3gihbc5d731084&from_date=0&to_date=1753545600&page=2&per_page=20&type=all&floor=all
        $query = [
            'from_date' => 0,
            'to_date' => 1753545600,
            'page' => 1,
            'per_page' => 20,
            'type' => 'all',
            'floor' => 'all'
        ];
        $query = array_merge($this->paramsCommon, $query);
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api-finance-t19.24hmoney.vn/v1/web/announcement/dividend-events', [
            'query' => $query
        ]);
        $res = $response->toArray();
        $data = [];
        if (! empty($res) && isset($res['status']) && $res['status'] == '200') {
            $data = $res['data'];
            foreach ($data as $k => $v) {
                $list['rich_text'] = $v['title'];
                $list['update_time'] = date('Y-m-d H:i:s', $v['published_date']);
                $list['top_value'] = 0;
                $list['url'] = '';
                $list['addtime'] = time();
                $list['resource_id'] = $v['id'];
                $row = (new CmsKuaixun())->where("resource_id", $v['id'])->find();
                if (empty($row)) {
                    (new CmsKuaixun())->save($list);
                }
            }
        }
        return $data;
    }

    public function getNewsList()
    {
        $query = [
            'c_num_row_per_page' => 20,
            'c_page' => 1,
            'last_article_id' => 0,
            'content' => 0
        ];
        $query = array_merge($this->paramsCommon, $query);
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.24hmoney.vn/v1/news/get_hot_news_by_category', [
            'query' => $query
        ]);
        $res = $response->toArray();
        $data = [];
        if (! empty($res) && isset($res['status']) && $res['status'] == '200') {
            $d = $res['data'];
            foreach ($d as $k => $v) {
                $list = [];
                $list['cid'] = 11;
                $list['model'] = 2;
                $list['title'] = $v['title'];
                $list['shorttitle'] = $v['title'];
                $list['uid'] = 31;
                $list['flag'] = 'c';
                $list['language'] = 'vi-nt';
                $list['status'] = 1;
                $list['cj_thumbs'] = $v['thumbnail'];
                $list['cj_url'] = '';
                $list['create_time'] = time();
                $list['cj_id'] = $v['id'];
                # 获取到详情了才能插入
                // $content = $this->getNews($v['id']);
                $get_one = Db::name('cms_document')->where('title', $v['title'])->find();
                if (! $get_one) {
                    $content = $this->getNews($v['id']);
                    if ($content) {
                        $aid = Db::name('cms_document')->insert($list, false, true);
                        Db::name('cms_document_news')->insert([
                            'aid' => $aid,
                            'content' => $content,
                            'summary' => $v['title'],
                            'language' => 'vi-nt'
                        ]);
                        echo '采集成功';
                    }
                }
                ;
            }
        }
        return $data;
    }

    public function getNews($newsId)
    {
        // https://api.24hmoney.vn/v1/news/get_news_detail?device_id=web1721875rl9h1mjk67jxyrkq5ypzehxi3gihbc5d731084&device_name=INVALID&device_model=Windows+10&network_carrier=INVALID&connection_type=INVALID&os=Edge&os_version=126.0.0.0&access_token=INVALID&push_token=INVALID&locale=vi&browser_id=web1721875rl9h1mjk67jxyrkq5ypzehxi3gihbc5d731084&c_news_id=2341607&preview=
        // $query = [];
        // $query = array_merge($this->paramsCommon, $query);
        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36 Edg/126.0.0.0',
            'Sec-Ch-Ua-Platform:' => '"Windows"',
            'Accept' => 'text/html'
        ];
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://24hmoney.vn/news/tay-to-chot-loi-c1a' . $newsId . '.html', [
            'headers' => $headers,
            'user_data' => [
                'encoding' => 'utf-8',
            ],
        ]);
        $res = '';
        if ($response->getStatusCode() == 200) {
            $res = $response->getContent();
            $res = mb_convert_encoding($res, 'UTF-8', 'UTF-8');
            $res = $this->getContent($res);
        }
        return $res;
    }

    public function getContent($html)
    {
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8" ?>'.$html);//处理乱码
        libxml_clear_errors();
        // 创建DOMXPath实例
        $xpath = new \DOMXPath($doc);
        // 查找所有具有class="news-content"的元素
        $nodes = $xpath->query('//div[contains(@class, "news-content")]');

        // 移除节点
        $rm = $xpath->query("//div[@class='category']");
        foreach ($rm as $node) {
            $node->parentNode->removeChild($node);
        }
        $rm = $xpath->query("//div[@class='corona-button-block']");
        foreach ($rm as $node) {
            $node->parentNode->removeChild($node);
        }
        $rm = $xpath->query('//a');
        foreach ($rm as $node) {
            $node->parentNode->removeChild($node);
        }
        // 遍历找到的节点并处理内容
        $node = $nodes[0]; // 取第一个匹配
        $innerHTML = '';
        foreach ($node->childNodes as $child) {
            $innerHTML .= $doc->saveHTML($child);
        }
        // $innerHTML = strip_tags($innerHTML, '<img><p><h1>');
        return $innerHTML;
    }
}
