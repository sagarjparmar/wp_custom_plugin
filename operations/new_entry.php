<div class="plugin_body">
    <div class="plugin_title_row">
        <h3 class="plugin_title">Entry Data</h3>
    </div>
    &nbsp;
    <div class="plugin_form_container">
        <div class="fieldscontainer">
            <div class="input_container">
                <div class="field_label">
                    <label for="title" class="label_title">Title</label>
                </div>
                <input type="text" class="title_input" id="title" placeholder="Enter title" required>
            </div>
        </div>
        &nbsp;
        <div class="fieldscontainer">
            <div class="input_container">
                <div class="field_label">
                    <label for="Description" class="label_title">Description</label>
                </div>
                <textarea class="description_input" id="discription" rows="3" required></textarea>
            </div>
        </div>
        &nbsp;
        <div class="fieldscontainer">
            <div class="input_container">
                <div class="field_label">
                    <label for="Description" class="label_title">Gender</label>
                </div>
                <div class="radio_fields">
                    <input class="" type="radio" name="gender" id="gender" value="M" checked>
                    <label class="" for="gridRadios1">Male</label>
                    <input class="" type="radio" name="gender" id="gender" value="F">
                    <label class="" for="gridRadios2">Female</label>
                </div>
            </div>
        </div>
        &nbsp;
        <div class="fieldscontainer">
            <div class="input_container">
                <div class="field_label">
                    <label for="Description" class="label_title">Sports</label>
                </div>
                <div>
                    <input class="" type="checkbox" name="sport" id="sport" value="Cricket">
                    <label class="" for="gridCheck1">Cricket</label>
                    <input class="" type="checkbox" name="sport" id="sport" value="Football">
                    <label class="" for="gridCheck1">Football</label>
                    <input class="" type="checkbox" name="sport" id="sport" value="Hockey">
                    <label class="" for="gridCheck1">Hockey</label>
                    <input class="" type="checkbox" name="sport" id="sport" value="Badminton">
                    <label class="" for="gridCheck1">Badminton</label>
                </div>
            </div>
        </div>
        &nbsp;
        <div class="fieldscontainer">
            <div class="input_container">
                <div class="field_label">
                    <label for="Description" class="label_title">Status</label>
                </div>
                <div class="radio_fields">
                <select class="" id="status">
                            <option value="H" selected>Hold</option>
                            <option value="P">Progress</option>
                            <option value="C">Complate</option>
                            <option value="R">Reject</option>
                    </select>
                </div>
            </div>
        </div>
        &nbsp;
        <div class="fieldscontainer">
            <div class="input_container">
                <div class="field_label">
                    <label for="Description" class="label_title">Upload Image</label>
                </div>
                <?php
                    $image_id = get_option( 'myprefix_image_id' );
                    if( intval( $image_id ) > 0 ) {
                        // Change with the image size you want to use
                        $image = wp_get_attachment_image( $image_id, 'medium', false, array( 'id' => 'myprefix-preview-image' ) );
                    } else {
                        // Some default image
                        $image = '<img id="myprefix-preview-image" style="height:150px; width:150px;" src="http://localhost/learn/wordpress/wp-content/uploads/2019/09/images.png" />';
                    // echo wp_get_attachment_url( 40 );
                    }
                    echo $image;
                ?>
                <input type="hidden" name="myprefix_image_id" id="myprefix_image_id" value="<?php echo esc_attr( $image_id ); ?>" class="regular-text" />
                <input type='button' class="btn btn-primary" value="<?php esc_attr_e( 'Select a image', 'mytextdomain' ); ?>" id="myprefix_media_manager"/>
            </div>
        </div>
        &nbsp;
        <div class="fieldscontainer">
            <div class="input_container">
                <div class="field_label">
                    <button type="button" id="save" class="btn btn-primary">Save</button>
                </div>            
            </div>
        </div>
    </div>
</div>
<table id="myTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>select</th>
                <th>Id</th>
                <th>Title</td>
                <th>Description</th>
                <th>Gender</th>
                <th>Sports</th>
                <th>images</th>
                <th>status</th>
            </tr>
        </thead>
        <tbody id="view_list">
            <tr>
            </tr>
        </tbody>
    </table>
&nbsp;


