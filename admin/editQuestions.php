<?php
include('includes/header.php');
include('includes/functions.php');
$question=null;
$answer1=null;
$answer2=null;
$answer3=null;
$answer4=null;
$answer1_id=null;
$answer2_id=null;
$answer3_id=null;
$answer4_id=null;
$correct= null;
$question_err=null;
$answer1_err=null;
$answer2_err=null;
$answer3_err=null;
$answer4_err=null;
$correct_err=null;
$yes=null;
$error=null;
if(isset($_GET['id']))
{
 $id=$_GET['id'];
 $sql="SELECT * from answers a INNER JOIN questions q on q.question_id= a.question_id INNER JOIN admin ad ON  ad.id = q.admin_id  where q.question_id=$id";
 $res=$con->query($sql);
 $i=1;
 while($r=$res->fetch_assoc()){
     $can=canManageQuestion($r['admin_role'],$r['admin_id']);
     if(!$can) {


             ?>
             <script>
                 window.location.href='questions';
             </script>
             <?php
             exit();
         }
     $question=$r['question'];
     ${'answer'.$i}=$r['answer'];
     if($r['is_correct'] == 1)
     {
         $correct=$i;
     }
     ${'answer'.$i.'_id'}=$r['answer_id'];
     $i++;
 }
}
else
{
?>
    <script>
        window.location.href='questions';
    </script>
<?php
}
if(isset($_POST['addquestion']))
{
    $question=toggleSlash($_POST['question'], 'add');
    $answer1=toggleSlash($_POST['answer1'], 'add');
    $answer2=toggleSlash($_POST['answer2'], 'add');
    $answer3=toggleSlash($_POST['answer3'], 'add');
    $answer4=toggleSlash($_POST['answer4'], 'add');
    $correct=$_POST['answer'];
    $valid=true;
    if(empty($question))
    {
        $valid=false;
        $question_err="Question is required";
    }
    if(empty($answer1))
    {
        $valid=false;
        $answer1_err="Answer 1 is required";
    }
    if(empty($answer2))
    {
        $valid=false;
        $answer2_err="Answer 2 is required";
    }
    if(empty($answer3))
    {
        $valid=false;
        $answer3_err="Answer 3 is required";
    }
    if(empty($answer4))
    {
        $valid=false;
        $answer4_err="Answer 4 is required";
    }
    if($correct == null)
    {
        $valid =false;
        $correct_err="Please choose one correct answer";
    }
    if($valid) {
        $sql = "SELECT * FROM `questions` WHERE `question` = '$question' AND `question_id` != $id";
        $res = $con->query($sql);
        if (($res->num_rows == 0 )) {
            $date = date('Y-m-d H:i:s');
            $admin = $_SESSION['uid'];
                        $sql = "UPDATE `questions` SET `question`='$question',`updated_at`='$date' WHERE `question_id`=$id";
                        $res=$con->query($sql);
            if ($res === true) {
                //check question id
                $question_id = $con->insert_id;
                for ($i = 1; $i <= 4; $i++) {
                    $its_correct = 0;
                    if ($i == $correct) {
                        $its_correct = 1;
                    }
                    $answer_tmp=${'answer' . $i};
                    $answer_tmp_id=${'answer' . $i . '_id'};
                    $sql = "UPDATE `answers` SET `answer`='$answer_tmp',`is_correct`=$its_correct,`updated_at`='$date' WHERE `answer_id`=$answer_tmp_id";
                    $con->query($sql);
                }
                $action =  'Updated Question: ' . $question;
                logEntry($action, $_SESSION['uid'], $con);
                $yes = "Question updated successfully";
            } else {
                $error = "Question update error. Try again.";
            }
        }
        else
        {
            $question_err="Question already exists";
        }
    }
}
?>

<div class="row pt-3">
    <div class="col-md-6 offset-md-3">
        <div class="card card-dark bg-dark text-white">
            <div class="card-header">
                <h3 class="card-title">Update Question</h3>
            </div>
            <form method="post" action="#">
                <div class="card-body">
                    <?php if($yes !=null ){ ?>
                    <p class="alert alert-success"><?php  echo $yes; ?></p>
                    <?php }
                    if($error !=null){
                    ?>
                    <p class="alert alert-danger"><?php  echo $error; ?></p>
                    <?php } 
                        $question=toggleSlash($question, 'remove');
                        $answer1=toggleSlash($answer1, 'remove');
                        $answer2=toggleSlash($answer2, 'remove');
                        $answer3=toggleSlash($answer3, 'remove');
                        $answer4=toggleSlash($answer4, 'remove');
                    ?>
                    <div class="form-group">
                        <label for="question">Question</label>
                        <input type="text" class="form-control" id="question" name="question" placeholder="Enter Question"  value="<?php echo str_replace("\'", "'", $question); ?>">
                        <span class="text-danger"><?php echo $question_err; ?></span>
                    </div>
                    <div class="ml-4 mr-4">
                    <p class="mt-5">Add Answers</p>
                    <div class="form-group">
                        <label for="answer1">Answer 1</label>
                        <input type="text" class="form-control" id="answer1" placeholder="Answer 1"  name="answer1"  value="<?php echo $answer1; ?>">
                        <span class="text-danger"><?php echo $answer1_err; ?></span>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="radio" class="custom-control-input" id="rad_answer1" name="answer" value="1" <?php if($correct==1){echo 'checked';} ?>>
                            <label class="custom-control-label" for="rad_answer1">Correct Answer</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="answer2">Answer 2</label>
                        <input type="text" class="form-control" id="answer2" placeholder="Answer 2" name="answer2"  value="<?php echo $answer2; ?>">
                        <span class="text-danger"><?php echo $answer2_err; ?></span>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="radio" class="custom-control-input" id="rad_answer2" name="answer" value="2" <?php if($correct==2){echo 'checked';} ?>>
                            <label class="custom-control-label" for="rad_answer2">Correct Answer</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="answer3">Answer 3</label>
                        <input type="text" class="form-control" id="answer3" placeholder="Answer 3" name="answer3"  value="<?php echo $answer3; ?>">
                        <span class="text-danger"><?php echo $answer3_err; ?></span>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="radio" class="custom-control-input" id="rad_answer3" name="answer" value="3" <?php if($correct==3){echo 'checked';} ?>>
                            <label class="custom-control-label" for="rad_answer3">Correct Answer</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="answer4">Answer 4</label>
                        <input type="text" class="form-control" id="answer4" placeholder="Answer 4" name="answer4"   value="<?php echo $answer4; ?>">
                        <span class="text-danger"><?php echo $answer4_err; ?></span>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="radio" class="custom-control-input" id="rad_answer4" name="answer" value="4" <?php if($correct==4){echo 'checked';} ?>>
                            <label class="custom-control-label" for="rad_answer4">Correct Answer</label>
                        </div>
                    </div>
                    <span class="text-danger"><?php echo $correct_err; ?></span>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" name="addquestion">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
