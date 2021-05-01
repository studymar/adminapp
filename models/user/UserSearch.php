<?php

namespace app\models\user;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\user\User;
use app\models\user\Useraddress;


/**
 * UserSearch represents the model behind the search form about `app\models\user\User`.
 */
class UserSearch extends User
{
    public $organisation; 
    public $rolename; 
    public $pagenumber = 1;
    public $itemsPerPage;
    public $orderBy;
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','pagenumber','itemsPerPage'], 'integer'],
            [['lastname', 'firstname', 'rolename', 'email', 'rolename', 'organisation', 'lastlogindate', 'orderBy'], 'string' ,'length'=>[0, 20], 'tooShort'=>'{attribute} muss zwischen {min} und {min} Zeichen lang sein.', 'tooLong'=>'{attribute} muss zwischen {min} und {min} Zeichen lang sein.' ],
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
     * @param array $params
     * @param array $withLimit default=true / Auf false setzen um alle Results zu bekommen
     *
     * @return ActiveDataProvider
     */
    public function search($params, $withLimit = true)
    {
    $query = User::find()
            ->select('user.id, lastname, firstname, email, organisation.name as organisation, organisation_id,'
                    . 'lastlogindate, locked, role_id, role.name as rolename')
            ->joinWith(['organisation','role']);
        
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
            'isvalidated' => 1,//nur validierte User
            'lastlogindate' => $this->lastlogindate,
            'created' => $this->created,
            'updated' => $this->updated,
            //'role_id' => $this->role_id,
            //'organisation_id' => $this->organisation_id,
        ]);
        
        $query->andWhere(
            ['is', 'user.deleted', null]
        );

        $query->andFilterWhere(['like', 'lastname', $this->lastname])
            ->andFilterWhere(['like', 'firstname', $this->firstname])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'role.name', $this->rolename])
            ->andFilterWhere(['like', 'organisation.name', $this->organisation]);

        return $query->asArray()->all();
    }
}
