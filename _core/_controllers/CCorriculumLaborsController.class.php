<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.03.13
 * Time: 18:04
 * To change this template use File | Settings | File Templates.
 */
class CCorriculumLaborsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Индивидуальные учебные планы");

        parent::__construct();
    }
    public function actionAdd() {
        $labor = new CCorriculumDisciplineLabor();
        $labor->discipline_id = CRequest::getInt("id");
        $this->setData("labor", $labor);
        $this->renderView("_corriculum/_labors/add.tpl");
    }
    public function actionEdit() {
        $labor = CCorriculumsManager::getLabor(CRequest::getInt("id"));
        $this->setData("labor", $labor);
        $this->renderView("_corriculum/_labors/add.tpl");
    }
    public function actionSave() {
        $labor = new CCorriculumDisciplineLabor();
        $labor->setAttributes(CRequest::getArray($labor::getClassName()));
        if ($labor->validate()) {
            $labor->save();
            $this->redirect("disciplines.php?action=edit&id=".$labor->discipline_id);
            return true;
        }
        $this->setData("labor", $labor);
        $this->renderView("_corriculum/_labors/add.tpl");
    }
    public function actionDel() {
        $labor = CCorriculumsManager::getLabor(CRequest::getInt("id"));
        $id = $labor->discipline_id;
        $labor->remove();
        $this->redirect("disciplines.php?action=edit&id=".$id);
    }
}
