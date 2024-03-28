let permissionCheckboxes = document.querySelectorAll(".control-center-wrapper .input-group-wrapper .input-group .permission-group .permission-checkbox");
let addNewAdminPermissionCheckbox = document.querySelector(".control-center-wrapper .input-group-wrapper .input-group .permission-group .add-new-admin-permission-checkbox");
let guestPermissionCheckbox = document.querySelector(".control-center-wrapper .input-group-wrapper .input-group .permission-group .guest-permission-checkbox");

function permissionEventListener() {
    // listen from add new admin permission checkbox side...
    addNewAdminPermissionCheckbox.addEventListener("change",()=>{
        console.log("changed");
        if ( addNewAdminPermissionCheckbox.checked ) {
            for ( let count = 0 ; count < permissionCheckboxes.length ; count++ ) {
                permissionCheckboxes[count].checked = true;
            }
            guestPermissionCheckbox.checked = false;
        }
    });
    // listen from guest permission checkbox side...
    guestPermissionCheckbox.addEventListener("change",()=>{
        if ( guestPermissionCheckbox.checked ) {
            for ( let count = 0 ; count < permissionCheckboxes.length ; count++ ) {
                permissionCheckboxes[count].checked = false;
            }
            guestPermissionCheckbox.checked = true;
        }
    });
    // listen from non-guest permission and non-add-new-admin permission checkbox side...
    permissionCheckboxes.forEach(checkbox => {
        if ( !checkbox.classList.contains("guest-permission-checkbox") ) {
            checkbox.addEventListener("change",event=>{
                if ( !addNewAdminPermissionCheckbox.checked ) {
                    guestPermissionCheckbox.checked = false;
                } else {
                    event.target.checked = true;
                }
            });
        }
    })
}

permissionEventListener();