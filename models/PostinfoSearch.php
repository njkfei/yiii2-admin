<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Postinfo;

/**
 * PostinfoSearch represents the model behind the search form about `app\models\Postinfo`.
 */
class PostinfoSearch extends Postinfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['pacname', 'version', 'version_in', 'title', 'zip_source', 'zip_name', 'themepic', 'theme_url'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Postinfo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'version_in' => $this->version_in,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'pacname', $this->pacname])
            ->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'zip_source', $this->zip_source])
            ->andFilterWhere(['like', 'zip_name', $this->zip_name])
            ->andFilterWhere(['like', 'themepic', $this->themepic])
            ->andFilterWhere(['like', 'theme_url', $this->theme_url]);

        return $dataProvider;
    }
}
