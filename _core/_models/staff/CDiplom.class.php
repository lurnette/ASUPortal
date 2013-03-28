<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.01.13
 * Time: 8:25
 * To change this template use File | Settings | File Templates.
 */
class CDiplom extends CActiveModel {
    protected $_table = TABLE_DIPLOMS;
    protected $_mark = null;
    protected $_student = null;
    protected $_person = null;
    protected $_previews = null;
    protected $_language = null;
    protected $_recomendationProtocol = null;
    protected $_confirmation = null;
    protected $_practPlace = null;
    protected $_reviewer = null;
    protected function relations() {
        return array(
            "mark" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_mark",
                "storageField" => "study_mark",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getMark"
            ),
            "student" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_student",
                "storageField" => "student_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getStudent"
            ),
            "person" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_person",
                "storageField" => "kadri_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "previews" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_previews",
                "relationFunction" => "getDiplomPreviews"
            ),
            "language" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_language",
                "storageField" => "foreign_lang",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getLanguage"
            ),
            "recomendationProtocol" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_recomendationProtocol",
                "storageField" => "protocol_2aspir_id",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getDepProtocol"
            ),
            "confirmation" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_confirmation",
                "storageField" => "diplom_confirm",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getDiplomConfirmation"
            ),
            "practPlace" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_practPlace",
                "storageField" => "pract_place_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getPracticePlace"
            ),
            "reviewer" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_reviewer",
                "storageField" => "recenz_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
        );
    }
    public function attributeLabels() {
        return array(
            "diplom_confirm" => "Утвержден",
            "dipl_name" => "Тема диплома",
            "pract_place" => "Место практики",
            "pract_place_id" => "Место практики",
            "kadri_id" => "Дипломный руководитель",
            "student_id" => "Студент",
            "group_id" => "Группа",
            "diplom_preview" => "Дата предзащиты",
            "date_act" => "Дата защиты",
            "foreign_lang" => "Иностранный язык",
            "protocol_2aspir_id" => "Протокол рекомендации в асипрантуру",
            "recenz_id" => "Рецензент",
            "study_mark" => "Оценка",
            "gak_num" => "Номер ГАК",
            "comment" => "Комментарий",
            "diplom_number" => "Номер диплома",
            "diplom_regnum" => "Регистрационный номер",
            "diplom_regdate" => "Дата решения ГАК",
            "diplom_issuedate" => "Дата выдачи"
        );
    }

    /**
     * @return CArrayList|null
     */
    protected function getDiplomPreviews() {
        if (is_null($this->_previews)) {
            $this->_previews = new CArrayList();
            /**
             * Если есть ключ диплома, то ищем по нему,
             * если нету, то ищем по студенту
             */
            foreach (CActiveRecordProvider::getWithCondition(TABLE_DIPLOM_PREVIEWS, "diplom_id = ".$this->getId())->getItems() as $item) {
                $preview = new CDiplomPreview($item);
                $this->_previews->add($preview->getId(), $preview);
            }
            if ($this->_previews->getCount() == 0) {
                if (!is_null($this->_student)) {
                    foreach (CActiveRecordProvider::getWithCondition(TABLE_DIPLOM_PREVIEWS, "student_id = ".$this->student->getId())->getItems() as $item) {
                        $preview = new CDiplomPreview($item);
                        $this->_previews->add($preview->getId(), $preview);
                    }
                }
            }
        }
        return $this->_previews;
    }

    /**
     * Дата последнего предпросмотра диплома в формате unix timestamp
     *
     * @return int
     */
    public function getLastPreviewDate() {
        $last = 0;
        foreach ($this->previews->getItems() as $preview) {
            if (strtotime($preview->date_preview) > $last) {
                $last = strtotime($preview->date_preview);
            }
        }
        return $last;
    }
}
