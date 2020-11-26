<?php
namespace Wuxian\Rbac;

use App\Model\Role;

class BaseTrait
{
	//切换model实例映射
    public function changeModel()
    {
        if ($this->driver instanceof \Hyperf\Database\Model\Model) {
            //手动注入model
            $this->model = $this->driver;
        } else {
            switch ($this->driver) {
                case 'App\Dao\ChatTypeDao':
                    $this->model = new \App\Model\ChatType();
                    break;
                case 'App\Dao\ChatTagDao':
                    $this->model = new \App\Model\ChatTag();
                    break;
                case 'App\Dao\ChatFriendTagDao':
                    $this->model = new \App\Model\ChatFriendTag();
                    break;
                case 'App\Dao\ChatAccountFriendDao':
                    $this->model = new \App\Model\ChatAccountFriend();
                    break;
                case 'App\Dao\ChatAccountExcelDao':
                    $this->model = new \App\Model\ChatAccountExcel();
                    break;
                case 'App\Dao\ChatAccountDao':
                    $this->model = new \App\Model\ChatAccount();
                    break;
                case 'App\Dao\ChatLogDao':
                    $this->model = new \App\Model\ChatLog();
                    break;
                case 'App\Dao\ChatListDao':
                    $this->model = new \App\Model\ChatList();
                    break;
                case 'App\Dao\ChatAccountGroupDao':
                    $this->model = new \App\Model\ChatAccountGroup();
                    break;
                case 'App\Dao\ChatAccountWhatsappDao':
                    $this->model = new \App\Model\ChatAccountWhatsapp();
                    break;
                case 'App\Dao\ChatReplyDao':
                    $this->model = new \App\Model\ChatReply();
                    break;
                case 'App\Dao\ChatRiskLogDao':
                    $this->model = new \App\Model\ChatRiskLog();
                    break;
                case 'App\Dao\StatisticsAccountDao':
                    $this->model = new \App\Model\StatisticsAccount();
                    break;
                case 'App\Dao\StatisticsEmpDao':
                    $this->model = new \App\Model\StatisticsEmp();
                    break;
                case 'App\Dao\ChatKeywordDao':
                    $this->model = new \App\Model\ChatKeyword();
                    break;
                case 'App\Dao\ChatKeywordSetDao':
                    $this->model = new \App\Model\ChatKeywordSet();
                    break;
                default:
                    throw new BusinessException(ErrorCode::ERR_BUESSUS, 'changeModel未注册无法映射');
            }
        }
    }
}
