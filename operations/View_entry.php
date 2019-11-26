&nbsp;
<div style="width:80%">
<div><h1>View Entries</h1></div>
<div class="action_row">
    <div class="crud_options_container">

        <label>show list of records</label>
        <select name="record_limit" id="record_limit">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
        &nbsp;
        <button name="Delete" id="Delete">Delete</button>
        &nbsp;
        <button name="Edit" id="Edit">Edit</button>
    </div>
</div>
<table id="myTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Mark</th>
                <th>Id</th>
                <th>Title</th>
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
</div>
<div class="crud_footer">
    <div class="crud_footer_container"><label for="list_of_records" class="listofrecored">Total numbers of records : <span id="records_num"> </span></label></div>
    <div class="pagination">Page: <button name="Prev" id="Prev">Prev</button><button name="next" id="next">Next</button></div>
</div>

