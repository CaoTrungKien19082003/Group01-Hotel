let general_data, contacts_data;

let contacts_settings=document.getElementById('contacts_settings');
let team_settings=document.getElementById('team_settings');
let member_name_input=document.getElementById('member_name_input');
let member_pic_input=document.getElementById('member_pic_input');

function get_general(){
    let site_title=document.getElementById('site_title');
    let site_about=document.getElementById('site_about');

    let site_title_input=document.getElementById('site_title_input');
    let site_about_input=document.getElementById('site_about_input');

    let shutdown_toggle=document.getElementById('shutdown_toggle');

    let xhr=new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);

    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload=function(){
        general_data=JSON.parse(this.responseText);
        site_title.innerText=general_data.site_title;
        site_about.innerText=general_data.site_about;

        site_title_input.value=general_data.site_title;
        site_about_input.value=general_data.site_about;

        if (general_data.shutdown==0){
            shutdown_toggle.checked=false;
            shutdown_toggle.value=0;
        }

        else{
            shutdown_toggle.checked=true;
            shutdown_toggle.value=1;
        }
    }

    xhr.send('get_general');

}

function update_general(site_title_info, site_about_info){
    let xhr=new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);

    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload=function(){
        var MyModal=document.getElementById('general_settings');
        var modal=bootstrap.Modal.getInstance(MyModal);
     
        if (this.responseText==1){
            alert('success', 'Changes have been made');
            get_general();
        }
    }
    xhr.send('site_title='+site_title_info+'&site_about='+site_about_info+'&update_general');
}

function update_shutdown(val){
    let xhr=new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);

    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload=function(){
        
        if (this.responseText==1 && general_data.shutdown==0){
            alert('success', 'Shutdown Mode On!');
            
        }
        else{
            alert('success', 'Shutdown Mode Off!');   
        }
        get_general();
    }
    xhr.send('update_shutdown='+val);
}


function get_contacts(){
    let contacts_p_id=['address', 'gmap', 'phone_01', 'phone_02', 'email', 'fb', 'tw', 'insta'];            
    let iframe=document.getElementById('iframe');

    let xhr=new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);

    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload=function(){
        contacts_data=JSON.parse(this.responseText);
        contacts_data=Object.values(contacts_data);

        for (i=0; i<contacts_p_id.length; i++){
            document.getElementById(contacts_p_id[i]).innerText=contacts_data[i+1];
        }

        iframe.src=contacts_data[9];
        contacts_inp(contacts_data);
    }
    xhr.send('get_contacts');            
}

function contacts_inp(data){
    let contacts_inp_id=['address_input', 'gmap_input', 'phone_01_input', 'phone_02_input', 'email_input', 'fb_input', 'tw_input', 'insta_input', 'iframe_input'];
    for (i=0; i<contacts_inp_id.length; i++){
        document.getElementById(contacts_inp_id[i]).value=data[i+1];
    }
}

contacts_settings.addEventListener('submit', function(e){
    e.preventDefault();
    update_contacts();
});

function update_contacts(){
    let index=['address', 'gmap', 'phone_01', 'phone_02', 'email', 'fb', 'tw', 'insta', 'iframe'];
    let contact_input_id=['address_input', 'gmap_input', 'phone_01_input', 'phone_02_input', 'email_input', 'fb_input', 'tw_input', 'insta_input', 'iframe_input'];
    let data_str="";
    for (i=0; i<index.length; i++){
        data_str+=index[i]+"="+document.getElementById(contact_input_id[i]).value+'&';
    }

    data_str+="update_contacts";
    let xhr=new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload=function(){
        if (this.responseText==1){
            alert('success', 'Changes have been made!');
            get_contacts();
        }
    }
   
    xhr.send(data_str);
};



team_settings.addEventListener('submit', function(e){
    e.preventDefault();
    add_member();
});

function get_members(){
    let xhr=new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload=function(){    
        document.getElementById('team_data').innerHTML=this.responseText;
    }

    xhr.send('get_members');
}

function add_member(){
    let data=new FormData();
    data.append('name', member_name_input.value);
    data.append('picture', member_pic_input.files[0]);
    data.append('add_member', '');

    let xhr=new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.onload=function(){
        if (this.responseText=='inv_file'){
            alert('error', 'The image you uploaded is invalid');
        }    
        else if (this.responseText=='inv_size'){
            alert('error', 'The image has exceeded 2MB maximum size!');
        }    
        else if (this.responseText=='upload_failed'){
            alert('error', 'The image failed to upload');
        }    
        else{
            alert('success', 'New member added!');
            member_name_input='';
            member_pic_input='';
            get_members();
        }
    }

    xhr.send(data);
}

function remove_member(val){
    let xhr=new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);

    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload=function(){  
        console.log(this.responseText);          
        if (this.responseText==1){
            
            alert('success', 'Member deleted!');
            get_members();
        }    
        else{
            alert('error', 'Server is currently down!');
        }
    }
    xhr.send('remove_member='+val);
}

window.onload=function(){
    get_general();
    get_contacts();
    get_members();
}



