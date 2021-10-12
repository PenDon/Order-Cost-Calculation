<?php

namespace app\commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Slince\Shopify\Exception\NotFoundException;
use Slince\Shopify\PrivateAppCredential;
use Symfony\Component\DomCrawler\Crawler;
use yii\db\Exception;

/**
 * Class CustomController
 * @package app\commands
 *
 * Script for migration of custom info
 * @author Peng <peng_mlyj@qq.com>
 */
class CustomController extends Controller
{

    /**
     * @var string crsf-token
     */
    public $token = 'NDjmbJ_RHdo5cCb6_2pusJxOTZxQz9qgSPT6wlaPyME';

    /**
     * @var string accessToken
     */
    public $accessToken = '99eddf1b754e8c4b43cc24f867c9ad54';

    /**
     * @var string ApiKey
     */
    public $ApiKey = 'ae16383f0fcf77503de385694102c554';

    /**
     * @var string ApiPassword
     */
    public $ApiPassword = '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';

    /**
     * @var string sharedSecret
     */
    public $sharedSecret = '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';

    /**
     * @var string sharedSecret
     */
    public $shopName = 'avrilname';

    /**
     * @var string cookie for request bold API
     */
    public $cookie = 'PHPSESSID=fvsggaiht2tdc0lbi0733cmc9q; __cf_bm=5e2974ffc393208d32c8b01690dc5efcd265c63c-1621911743-1800-AbZh4tlgKgVT4YXRSxosMOtMA9ZataJlOuMDb4Acjakjqru0uJ0YKuLt+SzZ3wQxC0kAjv46ai3M2d6n9Gie+Uc=';

    /**
     * Type array
     *
     * @return array
     */
    public function typeOptions()
    {
        return [
            "dropdown" => 'select',
            "textbox" => 'text',
            "checkbox" => 'checkboxes',
            "radio" => 'radio',
        ];
    }

    /***
     * Parse the [[optionsId]] and the [[productIds]] that apply this option from the given JSON file
     *
     * @return mixed
     */
    public function parseOptionIds()
    {
        $json = file_get_contents('./commands/config/customJson.json');

        return json_decode($json, true)['option_sets'];
    }

    /**
     * Shopify client initialization
     *
     * @return \Slince\Shopify\Client
     */
    public function shopify()
    {
//        $credential = new PublicAppCredential($this->accessToken);
        $credential = new PrivateAppCredential($this->ApiKey, $this->ApiPassword, $this->sharedSecret);
        return new \Slince\Shopify\Client($this->shopName . '.myshopify.com', $credential, [
            'meta_cache_dir' => './tmp',
        ]);
    }

    /**
     * Request custom information from API and insert to database
     *
     * @throws GuzzleException
     * @throws Exception
     */
    public function actionIndex()
    {
        $db = \Yii::$app->db;
        $cmd = \Yii::$app->db->createCommand(); //  database preparation

        $options = $this->parseOptionIds();  //  options parsed from customJson
        $shopify_client = $this->shopify();  //  shopify client

        $optionCount = 11111;
        $optionValueCount = 11111;
        $rawOptions = [];
        foreach ($options as $option) {
            // import single product
//            if ($option['id'] != 539231) {
//                continue;
//            }
            $products = json_decode($option['filtered_products'], true);
            $productIds = [];
            foreach ($products['products'] as $product) {  //  products that apply this option
                try {
                    $res = $shopify_client->getProductManager()->find($product['prod_id']);  //  request shopify product API with specified productId
                } catch (NotFoundException $e) {
                    $this->stdout("Error:" . $e->getMessage() . PHP_EOL . " The productId:" . $product['prod_id'] . " and the optionId:" . $option['id'] . PHP_EOL);
                    continue;
                }
                $title = $res->getTitle();  //  product name
//                $id = $db->createCommand("SELECT [[id]] FROM {{%posts}} WHERE [[post_title]] = :post_title")->bindParam(":post_title", $title);
//                var_dump($id->getRawSql());  //  sql for debug
                $id = $db->createCommand("SELECT [[id]] FROM {{%posts}} WHERE [[post_title]] = :post_title AND [[post_type]] = 'product'")->bindParam(":post_title", $title)->queryScalar();

                if ($id) {  //  no product
                    $this->stdout(">>>Found Product:【" . $title . "】" . PHP_EOL);
                    $productIds[] = $id;
                } else {
                    $this->stdout("Error: Not Found Product:【" . $title . "】" . PHP_EOL);
                }
            }

            if ($productIds) {  //  get options detail
                $optionId = $option['id'];
                $client = new Client();
                try {
                    $res = $client->request('GET', 'https://option.boldapps.net/v2/api/avrilname.myshopify.com/option_set_options/' . $optionId, [
                        'headers' => [
                            'x-csrf-token' => $this->token,
                            'cookie' => $this->cookie,
                        ]
                    ]);
                } catch (\Exception $e2) {
                    $this->stdout("Error:" . $e2->getMessage() . PHP_EOL);
                    $this->stdout("Failed to request option with ID :" . $optionId . ".Please update your cookie and crsf-token!" . PHP_EOL);
                    continue;
                }
                $rawOptions['options'] = json_decode($res->getBody()->getContents(), true)['content'];
                $rawOptions['productIds'] = $productIds;
                foreach ($productIds as $productId) {
                    $metaId = $db->createCommand("SELECT [[meta_id]] FROM {{%postmeta}} WHERE [[meta_key]] = '_wapf_fieldgroup' AND [[post_id]] = :post_id")->bindParam(":post_id", $productId)->queryScalar();
                    if ($metaId) {
                        $this->stdout("Already Exist." . PHP_EOL);
                        continue;
                    }
                    //  option insert
                    $productMetaJson["id"] = "p_" . $productId;
                    $productMetaJson = [
                        "type" => 'wapf_product',
                        "fields" => [],
                        "layout" => [
                            "labels_position" => "above",
                            "instructions_position" => "field",
                            "mark_required" => true,
                        ],
                        "rule_groups" => [
                            [
                                "rules" => [
                                    [
                                        "value" => [
                                            [
                                                "id" => $productId,
                                                "text" => ""
                                            ]
                                        ],
                                        "condition" => "product",
                                        "subject" => "product",
                                    ]
                                ],
                            ],
                        ],
                    ];
                    foreach ($rawOptions['options'] as $option) {
                        $optionValues = [];
                        if ($option['options_values']) {
                            foreach ($option['options_values'] as $key => $optionValue) {
                                $optionValues[] = [
                                    "slug" => "$optionValueCount",
                                    "label" => $optionValue['value'],
                                    "pricing_type" => "qt",
                                    "pricing_amount" => $optionValue['price'] / 100,
                                    "selected" => $optionValue['value'] == 'checked',
                                ];
                                $optionValueCount += 1;
                            }
                        }
                        $optionsInsert = [
                            "placeholder" => "",
                            "default" => "",
                        ];
                        if ($optionValues) {
                            $optionsInsert['choices'] = $optionValues;
                        }
                        $productMetaJson["fields"][] = [
                            "id" => "60ab1d4f" . $optionCount,
                            "label" => $option['public_name'],
                            "description" => "",
                            "type" => $this->typeOptions()[$option['type']],
                            "required" => $option['required'],
                            "class" => "",
                            "width" => "",
                            "options" => $optionsInsert,
                            "conditionals" => [],
                            "pricing" => [
                                "type" => "qt",
                                "amount" => 0,
                                "enable" => false,
                            ],
                        ];
                        $optionCount += 1;
                    }

                    $db->createCommand()->insert('{{%postmeta}}', [
                        "post_id" => $productId,
                        "meta_key" => "_wapf_fieldgroup",
                        "meta_value" => serialize($productMetaJson),
                    ])->execute();
                    $this->stdout(">>>Product:【" . $productId . "】import completed!" . PHP_EOL);
                }
            }
        }
        $this->stdout(">>>Done." . PHP_EOL);
    }

    /**
     * @throws GuzzleException
     */
    public function actionConditions()
    {
        $options = $this->parseOptionIds();
        $client = new Client();
        foreach ($options as $option) {
            $optionId = $option['id'];
            $res = $client->get('https://option.boldapps.net/conditions.php?option_set=' . $optionId, [
                'headers' => [
//                    'x-csrf-token' => $this->token,
                    'cookie' => $this->cookie,
                ]
            ]);
            $html = $res->getBody()->getContents();
//            var_dump($html);
            $crawler = new Crawler();
            $crawler->addContent($html);
            $ele = $crawler->filterXPath('//td[@id="id"]');
            if ($ele->count()) {
                $conditionIds = [];
                $ele->each(function ($e) use(&$conditionIds) {
                    $conditionIds[] = $e->text();
                });
                var_dump($conditionIds);
                foreach ($conditionIds as $conditionId) {
                    $res = $client->request('POST', 'https://option.boldapps.net/get_conditions.php', [
                        'form_params' => [
                            'id' => $conditionId,
                            'option_set' => $optionId
                        ]
                    ]);
                    $json = $res->getBody()->getContents();
                    $json = json_decode($json, true);
                    foreach ($json as $value) {
                        if ($value) {
                            var_dump($value);

                        } else {
                            $this->stdout("Error: No value for this conditionId:" . $conditionId . PHP_EOL);
                        }

                    }
                }
            } else {
                $this->stdout("Failed to get conditionId!");
            }
            die;
        }
    }
}