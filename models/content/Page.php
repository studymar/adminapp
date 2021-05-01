<?php

namespace app\models\content;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use app\models\user\User;
use app\models\forms\PaginationForm;
use app\models\content\Teaserlist;
use app\models\content\DocumentList;
use app\models\content\ImageList;
use app\models\content\Text;
use app\models\content\LinkItem;


/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property string $urlname
 * @property string $headline
 * @property integer $parentpage_id
 * @property string $linkname
 * @property integer $sort
 * @property string $seotitle
 * @property string $seokeywords
 * @property string $seodescription
 * @property integer $content_admin_only
 * @property integer $details_admin_only
 * @property string $created
 * @property integer $created_by
 * @property integer $release_id
 * @property integer $pagetype_id
 * @property string  $updated
 * @property integer $updated_by
 *
 * @property HistoryPage[] $historyPages
 * @property LinkItem[] $linkItems
 * @property Page $parentpage
 * @property Page[] $pages
 * @property Page[] $subpages
 * @property User $createdBy
 * @property User $updatedBy
 * @property Pagetype $pagetype
 * @property Release $release
 * @property PageAllowsTemplate[] $pageAllowsTemplates
 * @property Template[] $templates
 * @property PageHasTemplate[] $pageHasTemplates
 */
class Page extends \yii\db\ActiveRecord
{
    const COLUMN_URLNAME    = "urlname";
    public $hasTemplates    = [];
    
    public $isEditModus = false;
    
    /*
    const COLUMN_ID         = "id";
    const COLUMN_HEADLINE   = "headline";
    const COLUMN_LINKNAME   = "linkname";
    const COLUMN_SEOTITLE       = "seotitle";
    const COLUMN_SEOKEYWORDS    = "seokeywords";
    const COLUMN_SEODESCRIPTION = "seodescription";
    const COLUMN_CREATED        = "created";
    */
    
    public function __construct($parent_id = false){
        if($parent_id && is_numeric($parent_id))
            $this->parentpage_id = $parent_id;
        parent::__construct();
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['headline', 'seotitle', 'seokeywords', 'seodescription'], 'string'],
            [['parentpage_id', 'sort', 'details_admin_only', 'created_by', 'release_id', 'pagetype_id', 'updated_by'], 'integer'],
            [['pagetype_id','headline','release_id'], 'required'],
            [['content_admin_only'], 'boolean'],
            [['created', 'updated'], 'safe','except'=>'pagedetails'],
            [['release_id', 'pagetype_id'], 'required','except'=>'pagedetails'],
            [['urlname'], 'string', 'max' => 255],
            [['linkname'], 'string', 'max' => 25],
            [['urlname'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'urlname' => 'Urlname',
            'headline' => 'Überschrift',
            'parentpage_id' => 'Parentpage ID',
            'linkname' => 'Linkname',
            'sort' => 'Sort',
            'seotitle' => 'Titel für Suchmaschinen',
            'seokeywords' => 'Keywords',
            'seodescription' => 'Beschreibung',
            'content_admin_only' => 'Nur durch Admin änderbar?',
            'details_admin_only' => 'Edit Admin',
            'created' => 'Created',
            'created_by' => 'Created By',
            'release_id' => 'Release ID',
            'pagetype_id' => 'Pagetype ID',
            'updated' => 'Updated',
            'updated_by' => 'Updated By',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }    

    /*
    //angabe von feldern, die ausgegeben werden sollen
    public function fields()
    {
        return [
            // field name is the same as the attribute name
            'id',
            // field name is "email", the corresponding attribute name is "email_address"
            'email' => 'email_address',
            // field name is "name", its value is defined by a PHP callback
            'name' => function ($model) {
                return $model->first_name . ' ' . $model->last_name;
            },
        ];
    }
    * 
    */
    
    //herausfiltern von felder
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset(
                $fields['content_admin_only'], 
                $fields['details_admin_only'], 
                //$fields['created_by'], 
                $fields['updated_by'],
                $fields['release_id']
        );

        return $fields;
    }    
    
    //angabe, welche relations mit ausgegeben werden sollen
    /**/
    public function extraFields()
    {
        //return ['createdBy'];
        return [];
    } 
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryPages()
    {
        return $this->hasMany(HistoryPage::className(), ['parentpage_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinkItems()
    {
        return $this->hasMany(LinkItem::className(), ['target_page_id' => 'id']);
    }

    /**
     * Gibt die Parentpage zurück
     * @return \yii\db\ActiveQuery
     */
    public function getParentpage()
    {
        return $this->hasOne(Page::className(), ['id' => 'parentpage_id']);
    }

    /**
     * Gibt Subpages zurück
     * @return \yii\db\ActiveQuery
     */
    public function getSubpages()
    {
        return $this->hasMany(Page::className(), ['parentpage_id' => 'id'])->orderBy(['sort'=>SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagetype()
    {
        return $this->hasOne(Pagetype::className(), ['id' => 'pagetype_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelease()
    {
        return $this->hasOne(Release::className(), ['id' => 'release_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplates()
    {
        return $this->hasMany(Template::className(), ['id' => 'template_id'])->viaTable('page_has_template', ['page_id' => 'id']);
    }
    
    /**
     * Gibt die Navigation-Item zurück
     * @return \yii\db\ActiveQuery
     */
    public function getNavigation()
    {
        return $this->hasMany(Navigation::className(), ['id' => 'page_id']);
    }
    
    /**
     * Gibt die Subnavigation-Item zurück
     * @return \yii\db\ActiveQuery
     */
    public function getSubnavigation()
    {
        return $this->hasMany(Navigation::className(), ['id' => 'page_id']);
    }    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageHasTemplates()
    {
        return $this->hasMany(PageHasTemplate::className(), ['page_id' => 'id'])->orderBy(['sort'=>SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageHasTemplatesWithInstances()
    {
        return $this->hasMany(PageHasTemplate::className(), ['page_id' => 'id'])->with(['template','teaserlists','texts','imageLists','documentlists'])->orderBy(['sort'=>SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageHasTemplatesWithTemplates()
    {
        return $this->hasMany(PageHasTemplate::className(), ['page_id' => 'id'])->with(['template','texts','teaserlists','imageLists','documentlists'])->orderBy(['sort'=>SORT_DESC])->asArray();
    }

    
    /**
     * @inheritdoc
     * @return PageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PageQuery(get_called_class());
    }
    
    /**
     * Gibt die Homepage zurück
     * @return Page
     */
    public static function getHomepage()
    {
        return Page::findOne(1);
    }

    /**
     * Gibt die Sisterpages zurück
     * @return Page
     */
    public function getSisterpages()
    {
        if($this->parentpage_id == NULL)
            return Page::find()->where(['parentpage_id' => null])->orderBy(['sort'=>SORT_ASC])->all();
        else {
            $parent = $this->parentpage;
            if($parent) 
                return $parent->subpages;
        }
        return NULL;        
    }

    
    /**
     * Generiert ausgehend von der aktuellen Headline die SEO-Daten
     * @return boolean
     */
    public function generateSeodata(){
        $filterwords  = ['Der ','Die ','Das ', 'der ','die ','das ','des ','von ','und ','ist ','_'];
        $this->seotitle     = str_replace($filterwords, '', $this->headline);
        $this->seokeywords  = str_replace($filterwords, '', $this->headline);
        $this->urlname      = str_replace(' ', '-', strtolower($this->seotitle));
        $this->linkname     = substr($this->seotitle,0,25);
        return true;
    }

    /**
     * Passt den urlname leicht an, um Eindeutigkeit zu schaffen
     * @return boolean
     */
    public function rechangeUrlname(){
        $this->urlname      = $this->urlname."-".time();
        return true;
    }

    
    /**
     * Legt das aktuelle Object in der DB an
     * @return boolean
     */
    public function create($is_released = false){
        $release = Release::create($is_released);
        $this->release_id = $release->id;
        if($this->parentpage_id!=null)
            $this->sort       = Page::find()->where(['parentpage_id'=>$this->parentpage_id])->count()+1;
        $ret = $this->save();
        if($ret){
            //Templates laut pagetype_default anlegen
            $pagetypeDefaultTemplates = $this->pagetype->pagetypeDefaultTemplates;
            foreach ($pagetypeDefaultTemplates as $value ){
                $tpl = $value->template;
                //$release = Release::create($value->is_released_by_default);
                //$this->link('templates', $tpl, ['sort'=>count($this->templates)+1, 'release_id'=>$release->id] );

                $this->addTemplate($value->template_id);
                $this->refresh();
                
            }
        }
        return $ret;
    }

    /**
     * Erzeugt eine neue Seite
     * - SEO-Parameter werden anhand der Headline erzeugt
     * @param string $headline
     * @param boolean [optional] $is_released
     * @param int $pagetype [optional] Es werden die Templates zum Pagetype direkt mit erzeugt (z.b. Teaserliste oder Text / je nach Pagetype). Default ist Textseite
     * @return \app\models\content\Page
     */
    public static function createPage($headline, $is_released = false, $pagetype = 2){
        $page = new Page();
        $page->headline = $headline;
        $page->pagetype_id = $pagetype;
        $page->generateSeodata();
        $page->create($is_released);
        return $page;
    }

    
    /**
     * Legt ein Seite als Subpage der aktuellen Seite an
     * @param Page $subpage
     * @return boolean
     */
    public function addSubpage($subpage){
        $subpage->parentpage_id = $this->id;
        $subpage->sort          = count($this->subpages)+1; 
        return $subpage->save();
    }

    /**
     * Prüft, ob eine Seite subPages haben darf
     * z.b. darf die Home keine Subpages haben
     * @param Page $subpage
     * @return boolean
     */
    public function canHaveSubpages(){
        //ist nicht Homepage?
        if($this->pagetype != 1 && $this->parentpage_id == null){
            return true;
        }
        return false;
    }
    
    
    /**
     * Sortiert eine Unterseite von Position x zu y
     * kanna aufwärts oder abwärts sortieren
     * @param int $fromPosition
     * @param int $toPosition
     */
    public function sortTo($fromPosition, $toPosition){
        //unterscheiden, ob aufwärts oder abwärts sortiert werden soll
        if($toPosition > $fromPosition){
            return $this->sortUpTo($fromPosition, $toPosition);
        }
        else if($toPosition < $fromPosition){
            return $this->sortDownTo($fromPosition, $toPosition);
        }
    }

    /**
     * Sortiert eine Unterseite aufwärts von Position x zu y
     * @param int $fromPosition
     * @param int $toPosition
     */
    public function sortUpTo($fromPosition, $toPosition){
        Yii::info('Sorting Page under Parent '.$this->id." from position $fromPosition to position $toPosition");
        //umzusortierende Pages finden
        $changePages = Page::find()->where(
            'parentpage_id ='.$this->id.
            ' and sort >= '.$fromPosition.
            ' and sort <= '.$toPosition
        )->orderBy(['sort'=>SORT_ASC])->all();
        //umsortieren
        $isFirst = true;//erster Eintrag
        foreach($changePages as $item){
            if($isFirst){ //erster Eintrag ist der, der an Ziel sortiert werden soll
                $item->sort = $toPosition;
                if(!$item->save())
                    return false;
                $isFirst = false;
            }
            else { //alle anderen dafuer runtersortieren
                $item->sort--;
                if(!$item->save())
                    return false;
            }
        }
        return true;
    }

    /**
     * Sortiert eine Unterseite abwärts von Position x zu y
     * @param int $fromPosition
     * @param int $toPosition
     */
    public function sortDownTo($fromPosition, $toPosition){
        Yii::info('Sorting Page under Parent '.$this->id." from position $fromPosition to position $toPosition");
        //echo 'Sorting Page '.$page->id." from position $fromPosition to position $toPosition";
        //umzusortierende Pages finden
        $changePages = Page::find()->where(
            'parentpage_id = '.$this->id.
            ' and sort <= '.$fromPosition.
            ' and sort >= '.$toPosition
        )->orderBy(['sort'=>SORT_DESC])->all();
        //umsortieren
        $isFirst = true;//erster Eintrag
        foreach($changePages as $item){
            if($isFirst){ //erster Eintrag ist der, der an Ziel sortiert werden soll
                $item->sort = $toPosition;
                if(!$item->save())
                    return false;
                $isFirst = false;
            }
            else { //alle anderen dafuer runtersortieren
                $item->sort++;
                if(!$item->save())
                    return false;
            }
        }
        return true;
    }

    
    /**
     * Entfernt eine Seite aus dem Menü des Parent
     * (Löscht die Seite nicht, sie wird nur entfernt)
     * @param Page $parent
     * @param int $dropPosition
     */
    public function dropPage($parent, $dropPosition){
        $page = Page::find()->where([
            'parentpage_id'=>$parent->id,
            'sort'=>$dropPosition,
        ])->one();
        if( $page ){
            if( $this->decreaseFollowingPages($page) ){
                $page->sort = null;
                $page->parentpage_id = null;
                return $page->save();
            }
            /*
            else {
                Yii::error("Konnte Folgeseiten nicht decreasen zu Page $page->id");
                return false;
            }*/
        }
        else {
            throw new \yii\base\ErrorException("Page not found with parentpage_id $parent->id and sort $dropPosition");
        }
    }

    /**
     * Löscht eine Seite endgültig
     * @param Page $page
     */
    public function deletePage(){
        //link_item leeren
        $linkItems = $this->linkItems;
        foreach($linkItems as $linkitem){
            $linkitem->page_id = null;
            $linkitem->save();
        }
        //PageHasTemplate löschen => alle Items auf der Seite löschen
        $pageHasTemplates = $this->pageHasTemplates;
        foreach($pageHasTemplates as $pageHasTemplate){
            $pageHasTemplate->deletePageHasTemplate();
        }
        
        //jetzt Page löschen
        if( $erg = $this->delete() )
                return $erg;
        else
            throw new \yii\base\ErrorException("Konnte Seite nicht löschen: ".$erg);
    }    
    
    
    public function decreaseFollowingPages($page){
        //umzusortierende Pages finden
        $changePages = Page::find()->where(
            'parentpage_id = '.$page->parentpage_id.
            ' and sort > '.$page->sort
        )->orderBy(['sort'=>SORT_ASC])->all();
        //sonst umsortieren
        foreach($changePages as $item){
            $item->sort--;
            if(!$item->save($item)){
                //var_dump($item->getErrors());
                return false;
            }
        }
        return true;
    }    
    
    
    /**
     * Sucht Seiten, die der eingegebenen Headline entsprechen
     * Die Headline wird auch in seodescription, seotitle und seokeywords gesucht.
     * Sind diese Felder jedoch selbst gefüllt, wird der eingetragene Wert gesucht
     * @param PaginationForm $pagination
     */
    function search($pagination = FALSE){
        try {
            if($pagination){
                $itemsPerPage   = $pagination->itemsPerPage;
                $offset         = $pagination->getOffset();
            }
            /*
            $where = "";
            if($this->headline){
                $where.= "headline like '%".$this->headline."%'";
                $where.= "or seodescription like '%".($this->seodescription)?$this->seodescription:$this->headline."%'";
                $where.= "or seotitle like '%".($this->seotitle)?$this->seotitle:$this->headline."%'";
                $where.= "or seokeywords like '%".($this->seokeywords)?$this->seokeywords:$this->headline."%'";
            }
            return Page::find()->limit($itemsPerPage)->offset(0)->where($where)->all();
            */

            $query      = Page::find();
            if(isset($offset))
                $query->offset( $offset );
            if(isset($itemsPerPage))
                $query->limit( $itemsPerPage );

            
            if($this->headline != null){
                $query->orWhere("headline like '%".$this->headline."%'");
                $query->orWhere("headline like '".$this->headline."%'");
                $query->orWhere("headline like '%".$this->headline."'");
                $query->orWhere("urlname like '%".(($this->urlname)?$this->urlname:$this->headline)."%'");
                $query->orWhere("seodescription like '%".(($this->seodescription)?$this->seodescription:$this->headline)."%'");
                $query->orWhere("seotitle like '%".(($this->seodescription)?$this->seodescription:$this->headline)."%'");
                $query->orWhere("seokeywords like '%".(($this->seodescription)?$this->seodescription:$this->headline)."%'");
            }

            /*
            $query->orFilterWhere(['like', 'headline', $this->headline]);
            $query->orFilterWhere(['like', 'urlname', ($this->urlname)?$this->urlname:$this->headline]);
            $query->orFilterWhere(['like', 'seodescription', ($this->seodescription)?$this->seodescription:$this->headline]);
            $query->orFilterWhere(['like', 'seotitle', ($this->seodescription)?$this->seodescription:$this->headline]);
            $query->orFilterWhere(['like', 'seokeywords', ($this->seodescription)?$this->seodescription:$this->headline]);
            */
            $query->andWhere('id != 1');//startseite nicht finden (kann nicht eingebunden werden als subpage)
            $query->orderBy(['created'=> SORT_DESC]);

            return $query->all();


            //$countAllItems  = Page::find()->where($where)->count();
        } 
        catch (\yii\db\Exception $exc){
            Yii::error($exc->getMessage());
            return array();
        }
    }
    
    
    /**
     * Es wird ein Array mit dem Breadcrumb der Seite zurückgegeben
     * @param string $p Urlname einer Page 
     * @throws NotFoundHttpException
     */
    public function getBreadcrumb($page)
    {
        //breadcrumb einrichten, außer bei startseite
        $breadcrumb = array();
        
        $parent = $page;
        while($parent->parentpage_id != null){
            $breadcrumb[] = ['label' => $parent->parentpage->linkname, 'url' => [\yii\helpers\Url::to('@web/'.$parent->parentpage->urlname)]];
            $parent = $parent->parentpage;
        }
        //umdrehen
        $breadcrumb = array_reverse($breadcrumb);
        //aktuelle Seite hinzufügen
        if($page->id != 1)
            $breadcrumb[] = $page->linkname;
        
        return $breadcrumb;
    }    

    /**
     * Legt/Ergänzt ein Template auf einer Seite. Das neue Template wird oben 
     * ergänzt (kann danach manuell auf der Seite verschoben werden)
     * @param int $template_id Id des Templates
     * @param int $sort 
     * @throws NotFoundHttpException
     */
    public function addTemplate($template_id)
    {
        $page_has_template   = PageHasTemplate::create($this, $template_id, false, true);
        $objectname       = "\\app\\models\\content\\". ucfirst($page_has_template->template->objectname);
        $item       = new $objectname;
        $item->create($page_has_template->id);
        return $page_has_template;
    }    


    /**
     * Gibt die hinzufügbaren Templates zurück
     */
    public static function getAddableTemplates()
    {
        $templates = Template::find()->where(['=','isselectable',1])->all();
        return $templates;
    }
    
    /**
     * Gibt die möglichen Pagetypes für eine Seite zurück
     */
    public static function getPagetypes()
    {
        $templates = Pagetype::find()->where(['=','isselectable',1])->all();
        return $templates;
    }    
    
}
