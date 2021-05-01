<?php

namespace app\models\role;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\role\Role;
use app\models\user\User;

/**
 * RoleSearch represents the model behind the search form about `app\models\role\Role`.
 */
class RoleSearch extends Role
{
    public $countUsers;
    public $pagenumber;
    public $itemsPerPage;
    public $orderBy;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','countUsers','pagenumber','itemsPerPage'], 'integer'],
            [['name', 'orderBy'], 'string' ,'length'=>[0, 20], 'tooShort'=>'{attribute} muss zwischen {min} und {min} Zeichen lang sein.', 'tooLong'=>'{attribute} muss zwischen {min} und {min} Zeichen lang sein.' ],
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
     * @param array $withLimit default=true / Auf false setzen um alle Results zu bekommen
     *
     * @return \yii\db\ActiveQuery
     */
    public function search($params, $withLimit = true)
    {
        $query      = Role::find();
        $subQuery   = User::find()
        ->select('count(role_id) as countUsers, role_id')
        ->groupBy('role_id');
        $query->leftJoin(['countUsers'=>$subQuery], 'countUsers.role_id = id');
        //$query->leftJoin('user', 'user.role_id = role.id');
        //$query->groupBy('user.role_id');

        $query->select(['role.*','countUsers']);
        //$query->limit(false);
        if($withLimit){
            $query->limit($this->itemsPerPage);
            if($this->pagenumber > 1)
                $query->offset( (($this->pagenumber-1) * $this->itemsPerPage) );
        }
        if($this->orderBy)
            $query->orderBy($this->orderBy);
        else
            $query->orderBy('id');
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created' => $this->created,
            'countUsers' => $this->countUsers
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        //echo $query->createCommand()->sql;
        return $query->asArray()->all();
    }
    
    
}
