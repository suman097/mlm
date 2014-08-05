<?php

session_start();

class Members extends Controller {

    public function __construct() {

        $this->model("Members_model");
    }

    public function login() {


        if (sizeof($_POST)) {

            $username = $_POST['username'];
            $password = $_POST['password'];

            $result = $this->members_model->login($username, $password);


            if ($result[0]["count"]) {
                $_SESSION["user_id"] = $result[0]["user_id"];
                $_SESSION["user_role"] = $result[0]["role"];
                header("Location: /ewallet/retrieve");
            }
        }


        $page_template = "./views/members/login.php";
        require_once './views/_templates/masterPage.php';
    }

}

class Ewallet extends Controller {

    public function __construct() {

        $this->model("Ewallet_model");
    }

    public function create() {

        if (sizeof($_POST))
            $this->ewallet_model->create($_SESSION['user_id'], $_POST['bank_tran_id'], $_POST['payment'], $_POST['note']);



        $page_template = "./views/ewallet/create.php";
        require_once './views/_templates/masterPage.php';
    }

    public function retrieve() {
        $userId = $_SESSION["user_id"];
        $result = $this->ewallet_model->retrieve($userId);
        $page_template = "./views/ewallet/retrieve.php";
        require_once './views/_templates/masterPage.php';
    }

    public function update($accept) {
        $this->ewallet_model->update($accept);
    }

    public function acceptPayment() {

        if (sizeof($_POST))
            $this->update($_POST["accept"]);

        $result = $this->ewallet_model->retrieve();
        $page_template = "./views/ewallet/acceptPayment.php";
        require_once './views/_templates/masterPage.php';
    }

}

class Transaction extends Controller {

    public function __construct() {
        $this->model("Transaction_model");
        $this->model("Users");
        $this->model("Items_Packages");
    }

    public function create() {
        
        if(sizeof($_POST)){
            
        }
        
        $html = $this->users->retrieve();
        $items_html=$this->items_packages->retrieve();
        $page_template = "./views/transaction/create.php";
        require_once './views/_templates/masterPage.php';
    }

    public function retrieve() {

        if (sizeof($_GET)):
            $transaction_id = $_GET["id"];
            $result = $this->transaction_model->retrieve($transaction_id);
        else:
            $result = $this->transaction_model->retrieve("", $_SESSION["user_id"]);
        endif;

        $page_template = "./views/transaction/retrieve.php";
        require_once './views/_templates/masterPage.php';
    }

}

class purchase extends Controller {

    public function create() {

        $items = new Items();
        $html = $items->retrieve();
        $page_template = "./views/purchase/create.php";
        require_once './views/_templates/masterPage.php';
    }

}

class Items extends Controller {

    public function __construct() {

        $this->model("Items_model");
    }

    public function retrieve($ajaxify = FALSE) {
        $item_set = $this->items_model->retrieve();
        $html = "<select name='items[]'>";
        foreach ($item_set as $value) {
            $html.="<option value='" . $value["item_id"] . "'>" . $value["item_name"] . "</option>";
        }
        $html.="</select>";
        if ($ajaxify)
            echo $html;
        else
            return $html;
    }

}

class Users extends Controller {

    public function __construct() {
        $this->model("Users_model");
    }

    public function create() {

        if (sizeof($_POST)) {
            $username = $_POST["username"];
            $useremail = $_POST["useremail"];
            $password = $_POST["password"];
            $introducer = $_POST["introducer"];
            $this->users_model->create($introducer, $_SESSION["user_id"], $username, $useremail, $password);
        }

        $html = $this->retrieve();
        $page_template = "./views/users/create.php";
        require_once './views/_templates/masterPage.php';
    }

    public function retrieve() {
        return $this->users_model->retrieve();
    }

}

class Items_Packages extends Controller{
    
    public function __construct() {
        $this->model("Items_Packages_model");
        
    }
    public function retrieve(){
        return $this->items_packages_model->retrieve();
    }   
    
}


