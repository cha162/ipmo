document.getElementById('preview_record').addEventListener('click', function(){
    document.querySelector('.bg-modal').style.display = 'flex';

});


/* JOURNAL */
document.getElementById('preview_journal').addEventListener('click',
function(){
    document.querySelector('.bg-modal-journal').style.display = 'flex';
});

/*CERTIFICATE*/
document.getElementById('preview_certificate').addEventListener('click',
function(){
    document.querySelector('.bg-modal-certificate').style.display = 'flex';
});

/*NOTARIZED*/
document.getElementById('preview_notarized').addEventListener('click',
function(){
    document.querySelector('.bg-modal-notarized').style.display = 'flex';
});


/* UPLOAD RECORD */

document.querySelector('.button-group #upload_record').addEventListener('click', function(){
    document.querySelector('.modal-upload').style.display = 'flex';
});


