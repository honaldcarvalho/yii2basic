<?php

namespace croacworks\yii2basic\models;

use Exception;
use croacworks\yii2basic\controllers\AuthorizationController;
use croacworks\yii2basic\controllers\ControllerCommon;
use croacworks\yii2basic\models\ModelCommon;
use Yii;

/**
 * This is the model class for table "youtube".
 *
 * @property string $id
 * @property string $group_id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $thumbnail
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $status
 * 
 * @property Group $group
 */
class YoutubeMedia extends ModelCommon
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'youtube';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        foreach ($this->getAttributes() as $key => $value) {
            $scenarios[self::SCENARIO_DEFAULT][] = $key;
            $scenarios[self::SCENARIO_SEARCH][] = $key;
        }
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required','on'=>self::SCENARIO_DEFAULT],
            [['status'], 'integer'],
            [['id'], 'string', 'max' => 50],
            [['title', 'thumbnail','description'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['group_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Video ID',
            'title' => 'Title',
            'thumbnail' => 'Thumbnail',
            'status' => 'Status',
        ];
    }

    static function get_channel_videos($log = true,$group_id = null) {

        $results = [];

        if($group_id === null)
            $group_id = AuthorizationController::userGroup();

        $videos = [];
        $channelId = Parameter::findOne(['name'=>'youtube_channelId'])->value;
        $key = Parameter::findOne(['name'=>'youtube_key'])->value;

        $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&channelId={$channelId}&maxResults=15&order=date&key={$key}";
    
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $result = curl_exec($ch);
            $curl_errno = curl_errno($ch);
            $curl_error = curl_error($ch);
        } catch (Exception $ex) {
            $result = [];
        }

        if($result !== false && !empty($result)){

            $data  = json_decode($result);
            $group_id = AuthorizationController::userGroup();
            foreach ( $data->items as $item ) {
                $existingMedia = YoutubeMedia::findOne(['id' => $item->id->videoId]);
                if (!$existingMedia) {
                    if ( $item->id->kind === 'youtube#video' ) {
                        $video_id  = $item->id->videoId;
                        $title     = isset($item->snippet->title) ?  ControllerCommon::stripEmojis($item->snippet->title) : '';
                        $description     = isset($item->snippet->description) ?  ControllerCommon::stripEmojis($item->snippet->description) : '';
                        $publishedAt     = $item->snippet->publishedAt;
                        $thumbnail = '';
            
                        if ( isset( $item->snippet->thumbnails->maxres ) ) {
                            $thumbnail = $item->snippet->thumbnails->maxres->url;
                        } elseif ( isset( $item->snippet->thumbnails->standard ) ) {
                            $thumbnail = $item->snippet->thumbnails->standard->url;
                        //} elseif ( isset( $item->snippet->thumbnails->high ) ) {
                        //    $thumbnail = $item->snippet->thumbnails->high->url;
                        } elseif ( isset( $item->snippet->thumbnails->medium ) ) {
                            $thumbnail = $item->snippet->thumbnails->medium->url;
                        }
            
                        $videos[] = [
                            'id'   => $video_id,
                            'group_id'   => $group_id,
                            'title'     => $title,
                            'thumbnail' => $thumbnail,
                            'description' => $description,
                            'created_at' => date('Y-m-d H:i:s', strtotime($publishedAt)),
                            $result = Yii::$app->db->createCommand()->upsert('youtube', 
                            [
                                'id'   => $video_id,
                                'title'     => $title,
                                'thumbnail' => $thumbnail,
                                'description' => $description,
                                'created_at' => date('Y-m-d H:i:s', strtotime($publishedAt)),
                            ]
                            )->execute()
                        ];
                        if($result){
                            echo "Media {$item->id->videoId} added.\n";
                        }else{
                            echo "Erro on add Media {$item->id->videoId}.\n";
                        }
            
                    }
                } else {
                    $videos[$item->id->videoId] = "Media {$item->id->videoId} already exists.\n";
                    echo "Media {$item->id->videoId} already exists.\n";
                }
            }

        }  

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $videos;
    }

}
