<?php

namespace app\modules\admin\modules\mail\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\modules\mail\models\Email;

/**
 * EmailSearch represents the model behind the search form of `app\modules\admin\modules\mail\models\Email`.
 */
class EmailSearch extends Email
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'addresser_id', 'addressee_id', 'type', 'status', 'template_id'], 'integer'],
            [['remark'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Email::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
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
            'addresser_id' => $this->addresser_id,
            'addressee_id' => $this->addressee_id,
            'template_id' => $this->template_id,
            'type' => $this->type,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }

}
