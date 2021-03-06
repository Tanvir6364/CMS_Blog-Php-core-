<?php include 'inc/header.php' ?>

    <div class="grid_10">
        <div class="box round first grid">
            <h2>Update Post</h2>
            <?php
            if (!isset($_GET['postid']) || $_GET['postid'] == null) {
                echo "<script>window.location='postlist.php';</script>";
            } else {
                $id = $_GET['postid'];
            }
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $title = mysqli_real_escape_string($db->link, $_POST['title']);
                $cat = mysqli_real_escape_string($db->link, $_POST['cat']);
                $body = mysqli_real_escape_string($db->link, $_POST['body']);
                $tags = mysqli_real_escape_string($db->link, $_POST['tags']);
                $author = mysqli_real_escape_string($db->link, $_POST['author']);
                $userid = mysqli_real_escape_string($db->link, $_POST['userid']);

                //File Upload///
                $permited = array('jpg', 'jpeg', 'png', 'gif');
                $file_name = $_FILES['image']['name'];
                $file_size = $_FILES['image']['size'];
                $file_temp = $_FILES['image']['tmp_name'];

                $div = explode('.', $file_name);
                $file_ext = strtolower(end($div));
                $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
                $upload_image = 'upload/' . $unique_image;
                if ($title == "" || $cat == "" || $body == "" || $tags == "" || $author == "" ) {
                    echo "<span style='color: red;font-size: 18px;'>File Must Not Be Empty!!!</span>";
                }else {
                    if (!empty($file_name)) {
                        if ($file_size > 2097134) {
                            echo "<span style='color: red;font-size: 18px;'>File Size Should Be Less Then 2MB....</span>";
                        } elseif (in_array($file_ext, $permited) == false) {
                            echo "<span style='color: red;font-size: 18px;'>You Can Upload Only:- " . implode(',', $permited) . "</span>";
                        }else {
                        move_uploaded_file($file_temp, $upload_image);
                        $query="update tbl_post set
                        cat='$cat',title='$title',body='$body',image='$upload_image',author='$author',tags='$tags',userid='$userid' where id='$id'";

                        $updated = $db->insert($query);
                        if ($updated) {
                            echo "<span style='color: green;font-size: 18px;'>Data Successfully Inserted</span>";
                        } else {
                            echo "<span style='color: red;font-size: 18px;'>Data Not Inserted</span>";
                        }
                    }
                }else{
                        $query="update tbl_post set
                        cat='$cat',title='$title',body='$body',author='$author',tags='$tags',userid='$userid' where id='$id'";

                        $updated = $db->insert($query);
                        if ($updated) {
                            echo "<span style='color: green;font-size: 18px;'>Data Successfully Inserted</span>";
                        } else {
                            echo "<span style='color: red;font-size: 18px;'>Data Not Inserted</span>";
                        }
                    }
                }


            }
            ?>
            <div class="block">
                <?php
                $query = "select * from tbl_post where id='$id' order by id DESC ";
                $getpost = $db->select($query);
                while ($postresult = $getpost->fetch_assoc()) {

                    ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <table class="form">

                            <tr>
                                <td>
                                    <label>Title</label>
                                </td>
                                <td>
                                    <input type="text" name="title" value="<?php echo $postresult['title']; ?>"
                                           class="medium"/>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>Category</label>
                                </td>
                                <td>

                                    <select id="select" name="cat">

                                        <?php
                                        $query = "select * from tbl_category order by id desc";
                                        $found = $db->select($query);
                                        if ($found) {
                                            while ($result = $found->fetch_assoc()) {
                                                ?>
                                                <option
                                                    <?php
                                                    if ($postresult['cat'] == $result['id']) {
                                                        ?>
                                                        selected="selected"
                                                    <?php } ?>

                                                        value="<?php echo $result['id']; ?>"><?php echo $result['name']; ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>Upload Image</label>
                                </td>
                                <td>
                                    <img src="<?php echo $postresult['image']; ?>" height="50px" width="100px"><br>
                                    <input type="file" name="image"/>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding-top: 9px;">
                                    <label>Content</label>
                                </td>
                                <td>
                                    <textarea class="tinymce" name="body"><?php echo $postresult['body']; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Tags</label>
                                </td>
                                <td>
                                    <input type="text" name="tags" value="<?php echo $postresult['tags']; ?>"
                                           placeholder="Enter Tags..." class="medium"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Author</label>
                                </td>
                                <td>
                                    <input type="text" name="author" value="<?php echo $postresult['author']; ?>"
                                           placeholder="Enter Author Name..." class="medium"/>
                                    <input type="hidden"  name="userid" value="<?php echo (Session::get('userRole')); ?>" class="medium" />
                                </td>
                            </tr>

                            <tr>
                                <td></td>
                                <td>
                                    <input type="submit" name="submit" Value="Save"/>
                                </td>
                            </tr>
                        </table>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Load TinyMCE -->
    <script src="js/tiny-mce/jquery.tinymce.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            setupTinyMCE();
            setDatePicker('date-picker');
            $('input[type="checkbox"]').fancybutton();
            $('input[type="radio"]').fancybutton();
        });
    </script>

<?php include 'inc/footer.php' ?>