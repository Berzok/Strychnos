<?php

namespace App\Service;

use App\Service\Base\BasePixivAPI;
use Curl\Curl;
use Exception;

class PixivAPI extends BasePixivAPI {

    /**
     * @var string
     */
    protected $api_filter = 'for_ios';

    /**
     * @var array
     */
    protected array $headers = array(
        'Authorization' => 'Bearer WHDWCGnwWA2C8PRfQSdXJxjXp0G6ULRaRkkd6t5B6h8',
    );

    /**
     * @var array|string[]
     */
    protected array $noneAuthHeaders = array(
        'User-Agent' => 'PixivIOSApp/6.7.1 (iOS 10.3.1; iPhone8,1)',
        'App-OS' => 'ios',
        'App-OS-Version' => '10.3.1',
        'App-Version' => '6.9.0',
    );

    /**
     * ユーザーの詳細
     *
     * @param string $user_id
     * @return array
     * @throws Exception
     */
    public function user_detail(string $user_id): array {
        return $this->fetch('/v1/user/detail', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'user_id' => $user_id,
                'filter' => $this->api_filter,
            ),
        ));
    }

    /**
     * ユーザーのイラスト
     *
     * @param string $user_id
     * @param integer $page
     * @param string $type
     * @return array
     * @throws Exception
     */
    public function user_illusts(string $user_id, int $page = 1, string $type = 'illust'): array {
        return $this->fetch('/v1/user/illusts', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'user_id' => $user_id,
                'type' => $type,
                'offset' => ($page - 1) * 30,
                'filter' => $this->api_filter,
            ),
        ));
    }

    /**
     * 検索イラスト
     *
     * @param $word
     * @param integer $page
     * @param string $search_target
     *                     partial_match_for_tags
     *                     exact_match_for_tags
     *                     title_and_caption
     * @param string $sort
     *                     date_desc
     *                     date_asc
     * @param string|null $duration
     *                     within_last_day
     *                     within_last_week
     *                     within_last_month
     * @return array
     * @throws Exception
     */
    public function search_illust($word, int $page = 1, string $search_target = 'partial_match_for_tags', string $sort = 'date_desc', string $duration = null): array {
        $body = array(
            'word' => $word,
            'search_target' => $search_target,
            'sort' => $sort,
            'offset' => ($page - 1) * 30,
            'filter' => $this->api_filter,
        );
        if ($duration != null) {
            $body['duration'] = $duration;
        }
        return $this->fetch('/v1/search/illust', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => $body,
        ));
    }

    /**
     * ユーザーのマーク付きイラスト
     *
     * @param string $user_id
     * @param string $restrict
     * @return array
     * @throws Exception
     */
    public function user_bookmarks_illust(string $user_id, string $restrict = 'public'): array {
        return $this->fetch('/v1/user/bookmarks/illust', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'user_id' => $user_id,
                'restrict' => $restrict,
                'filter' => $this->api_filter,
            ),
        ));
    }

    /**
     * イラストの詳細
     *
     * @param string $illust_id
     * @return array
     * @throws Exception
     */
    public function illust_detail(string $illust_id): array {
        return $this->fetch('/v1/illust/detail', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'illust_id' => $illust_id,
            ),
        ));
    }

    /**
     * イラストのコメント
     *
     * @param string $illust_id
     * @param integer $page
     * @param boolean $include_total_comments
     * @return array
     * @throws Exception
     */
    public function illust_comments(string $illust_id, int $page = 1, bool $include_total_comments = true): array {
        return $this->fetch('/v1/illust/comments', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'illust_id' => $illust_id,
                'offset' => ($page - 1) * 30,
                'include_total_comments' => true,
            ),
        ));
    }

    /**
     * 関連イラストリスト
     *
     * @param string $illust_id
     * @param array|null $seed_illust_ids
     * @return array
     * @throws Exception
     */
    public function illust_related(string $illust_id, array $seed_illust_ids = null): array {
        $body = array(
            'illust_id' => $illust_id,
            'filter' => $this->api_filter,
        );
        if (is_array($seed_illust_ids)) {
            $body['seed_illust_ids[]'] = $seed_illust_ids;
        }
        return $this->fetch('/v2/illust/related', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => $body,
        ));
    }

    /**
     * ランキングイラスト一覧
     *
     * @param string $mode
     *                         day
     *                         week
     *                         month
     *                         day_male
     *                         day_female
     *                         week_original
     *                         week_rookie
     *                         day_manga
     * @param integer $page
     * @param null $date YYYY-MM-DD
     * @return mixed [type]
     * @throws Exception
     */
    public function illust_ranking(string $mode = 'day', int $page = 1, $date = null): mixed {
        $body = array(
            'mode' => $mode,
            'offset' => ($page - 1) * 30,
            'filter' => $this->api_filter,
        );
        if ($date != null) {
            $body['date'] = $date;
        }
        return $this->fetch('/v1/illust/ranking', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => $body,
        ));
    }

    /**
     * トレンドタグ
     * {}
     * @return array
     * @throws Exception
     */
    public function trending_tags_illust(): array {
        return $this->fetch('/v1/trending-tags/illust', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'filter' => $this->api_filter,
            ),
        ));
    }

    /**
     * ユーザーのフォロー
     * @param string $user_id
     * @param string $restrict
     * @param integer $page
     * @return array
     * @throws Exception
     */
    public function user_following(string $user_id, string $restrict = 'public', int $page = 1): array {
        return $this->fetch('/v1/user/following', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'user_id' => $user_id,
                'restrict' => $restrict,
                'offset' => ($page - 1) * 30,
            ),
        ));
    }

    /**
     * ユーザーのフォロワー
     * @param string $user_id
     * @param integer $page
     * @return array
     * @throws Exception
     */
    public function user_follower(string $user_id, int $page = 1): array {
        return $this->fetch('/v1/user/follower', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'user_id' => $user_id,
                'filter' => $this->api_filter,
                'offset' => ($page - 1) * 30,
            ),
        ));
    }

    /**
     * ユーザーのマイピク
     * @param string $user_id
     * @param integer $page
     * @return array
     * @throws Exception
     */
    public function user_mypixiv(string $user_id, int $page = 1): array {
        return $this->fetch('/v1/user/mypixiv', array(
            'method' => 'get',
            'headers' => array_merge($this->noneAuthHeaders, $this->headers),
            'body' => array(
                'user_id' => $user_id,
                'offset' => ($page - 1) * 30,
            ),
        ));
    }


    /**
     * @throws Exception
     */
    public function fetch_source(string $uri) {

        $method = 'get';
        $headers = array_merge($this->noneAuthHeaders, $this->headers, [
            'Content-Type' => 'image/png',
            'referer' => 'https://www.pixiv.net/'
        ]);

        $array = explode('.', $uri);
        $extension = array_pop($array);

        //$curl_log = fopen($_SERVER['DOCUMENT_ROOT'] . "/../src/Service/curl.html", 'w'); // open file for READ and write

        $curl = new Curl();
        $curl->setOpt(CURLOPT_CONNECTTIMEOUT, 10);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
        $curl->setOpt(CURLOPT_REFERER, 'https://www.pixiv.net/');
        $curl->setOpt(CURLOPT_HEADER, 0);
        $curl->setOpt(CURLINFO_HEADER_OUT, true);
        $curl->setOpt(CURLOPT_VERBOSE, true);

        if (is_array($headers)) {
            foreach ($headers as $key => $value) {
                $curl->setHeader($key, $value);
            }
        }

        $curl->$method($uri);
        $result = $curl->response;

        $curl->close();
        //$array = json_decode(json_encode($result), true);
        return $result;

        $base64 = $this->imageToBase64($result, $extension);
        return $base64;
    }

    /**
     * @param $image
     * @return array
     */
    public function imageToBase64($image, string $extension): array {

        $imageData = base64_encode($image);
        $mime_types = array(
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'odt' => 'application/vnd.oasis.opendocument.text ',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'gif' => 'image/gif',
            'jpg' => 'image/jpg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'bmp' => 'image/bmp'
        );

        //$ext = parse_url($image, PATHINFO_EXTENSION);

        if (array_key_exists($extension, $mime_types)) {
            $a = $mime_types[$extension];
        }

        return array(
            'data' => $a,
            'base64' => $imageData
        );
    }

}
