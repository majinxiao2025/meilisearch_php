<?php

/**
 * Copyright © 2017-2025 Braveten Technology Co., Ltd.
 * Engineer: Makin
 * Date: 2025/7/28
 * Time: 15:05
 */

use Meilisearch\Client;
use Meilisearch\Contracts\DocumentsQuery;

class cls_meilisearch
{
    private $index;
    /**
     * @return void
     * 操作范例
     */
    public function __construct(){
        require_once __DIR__. '/meilisearch/vendor/autoload.php';
        $client = new Client('http://127.0.0.1:7700', 'At43V9WVh0yRI_piRNdNs4aTJZHnuWLm74IPV-PtahE');
        $this->index = $client->index('btba');
    }
    private function example()
    {
        /*
         * 在您决定允许用户按哪些字段进行排序后，您必须将其属性添加到sortableAttributes索引设置中。
         * 配置美丽搜索在搜索时进行排序
         * $client->index('books')->updateSortableAttributes([
            'author',
            'price'
        ]);*/
        /*$client->index('movies')->updateSettings([
            //按重要性排序的规则列表
            'rankingRules' => [
                'words',
                'typo',
                'proximity',
                'attribute',
                'sort',
                'exactness',
                'release_date:desc',
                'rank:desc'
            ],
            //搜索返回具有给定字段的不同值的文档
            'distinctAttribute' => 'movie_id',
            //["*"]	按重要性排序的用于搜索匹配查询词的字段
            'searchableAttributes' => [
                'title',
                'overview',
                'genres'
            ],
            //返回文档中显示的字段
            'displayedAttributes' => [
                'title',
                'overview',
                'genres',
                'release_date'
            ],
            //在搜索查询中出现时被 Meilisearch 忽略的单词列表
            'stopWords' => [
                'the',
                'a',
                'an'
            ],
            //	对搜索结果进行排序时使用的属性
            'sortableAttributes' => [
                'title',
                'release_date'
            ],
            //类似处理的相关词列表
            'synonyms' => [
                'wolverine' => ['xmen', 'logan'],
                'logan' => ['wolverine']
            ],
            //拼写错误容忍度设置
            'typoTolerance' => [
                'minWordSizeForTypos' => [
                    'oneTypo' => 8,
                    'twoTypos' => 10
                ],
                'disableOnAttributes' => ['title']
            ],
            //分页设置
            'pagination' => [
                'maxTotalHits' => 5000
            ],
            //刻面设置
            'faceting' => [
                'maxValuesPerFacet' => 200
            ],
            //搜索查询的最长持续时间
            'searchCutoffMs' => 150
        ]);*/
    }
    public function index()
    {
        //$fields=[ 'did','tags','title', 'image','director','stars','country',''];

        echo '<pre>';

        /*//修改最大搜索返回结果maxTotalHits为10万
        $index->updateSettings(['pagination' => [
                'maxTotalHits' => 10000
            ]
        ]);*/
        //获取设置
        //$get = $index->getSettings();
        //print_r($get);
        //禁止id被索引
        //displayedAttributes
        //['did,title,image,director,stars,country,score,showdate,text,updated']
        $get = $this->index->updateDisplayedAttributes(['did','title','image','director','stars','country','score','showdate','torrents','update']);
        //$get = $this->index->getDisplayedAttributes();
        print_r($get);
        //对想要的字段进行排序
        //$res=$index->updateSortableAttributes(['id','update']);
        //print_r($res);exit();
        //
        /*$index->updateFilterableAttributes([
            'display',[
                'attributePatterns'=>['tags','stars','director','country'],
                'features' => [
                    'facetSearch' => true,
                    'filter' => [
                        'equality' => true,
                        'comparison' => true,
                    ],
                ]
            ]
        ]);*/
        //$get = $index->getFilterableAttributes();
        //获取可过滤的属性

        //$get = $client->index('btba')->getFilterableAttributes();
        //$get=$index->search('', ['sort' => ['id:desc'],'limit' => 15,'filter'=>'display = 1']);

        //$get->getHit();
        //$get=$index->getDocuments((new DocumentsQuery())->setFilter(['display = 1'])->setLimit(5));
        //$get = $index->getDocuments();
        //var_dump($get);
        //print_r($get);
        echo '</pre>';

       /* $documents = [
            ['id' => 1,  'title' => 'Carol', 'genres' => ['Romance, Drama']],
            ['id' => 2,  'title' => 'Wonder Woman', 'genres' => ['Action, Adventure']],
            ['id' => 3,  'title' => 'Life of Pi', 'genres' => ['Adventure, Drama']],
            ['id' => 4,  'title' => 'Mad Max: Fury Road', 'genres' => ['Adventure, Science Fiction']],
            ['id' => 5,  'title' => 'Moana', 'genres' => ['Fantasy, Action']],
            ['id' => 6,  'title' => 'Philadelphia', 'genres' => ['Drama']],
        ];

# If the index 'movies' does not exist, Meilisearch creates it when you first add the documents.
        $index->addDocuments($documents); // => { "uid": 0 }*/
    }
    public function search($query,int $limit): object
    {
        $page = get('page')?:1;
        if(!is_numeric($page)){
            exit('Invalid page error');
        }
        $response = $this->index->search($query, ['limit' => $limit,'offset'=>$limit * ($page - 1),'filter'=>'display = 1']);
        return (object)[
            'hits'=>$response->getHits(),
            'total'=>$response->getEstimatedTotalHits(),
            'query'=>$response->getQuery(),
        ];
    }

    /**
     * @return array
     * 清空数据
     * 2025-07-31 11:36:42
     */
    public function clear(): array
    {
        return $this->index->deleteAllDocuments();
    }
}