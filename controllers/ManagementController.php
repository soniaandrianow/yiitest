<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ManagementController implements the CRUD actions for Users model.
 */
class ManagementController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new \app\models\UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //$oldmodel = $this->findModel($id);
        $model->scenario = Users::SCENARIO_UPDATE_ALL;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('conf', 'Data updated successfully!');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelByUsername($username)
    {
        if (($model = Users::findOne(['username' => $username])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionTest($username)
    {
        return $this->render('test', [
                    'model' => $this->findModelByUsername($username),
        ]);
    }

    public function actionSql()
    {
        $query = new \yii\db\Query();
//        $query->select(['id', 'username'])
//                ->from(['user'])
//                ->where(['and', ['or', ['and', ['in', 'id', [1, 2, 5]], ['like', 'username', 'afa']], ['and', ['like', 'username', 'drian'], ['>', 'created_at', '1234567']]], ['not', ['flag' => null]]])
//                //->andWhere(['like', 'username', 'afa'])
//                //->orWhere(['and', ['like', 'username', 'drian'], ['created_at' > '1234567']])
//                //->andWhere(['not', ['flag' => null]])
//                ->orderBy(['id' => SORT_DESC, 'username' => SORT_ASC])
//                ->groupBy(['flag'])
//                ->limit(20);
//        //->createCommand()
//        //->rawSql;

        $query->select(['id', 'username'])
                ->from('user')
                ->where(['id' => [1, 2, 5]])
                ->andWhere(['or', ['like', 'username', 'afa'], ['and', ['like', 'username', 'drian'], ['>', 'created_at', '1234567']]])
                ->andWhere(['not', ['flag' => null]])
                ->orderBy(['id' => SORT_DESC, 'username' => SORT_ASC])
                ->groupBy('flag')
                ->limit(20);

        $query2 = $query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql;

        return $this->render('sql', ['query' => $query2]);
    }

}
