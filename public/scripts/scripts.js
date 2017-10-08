
function destruye(id, nombre) {
    var url = "destruye.php";

    $.ajax({
        url: url,
        type: "POST",
        data: {
            'nombre' : nombre
        },
        success: function(ret)
        {
            if ("ok" in ret) {
                $('#' + id).removeClass('existe').addClass('noExiste');
            } else {
                alert(ret['error']); // show response from the php script.
            }
        }
    });
}

function processFileUpload(target, droppedFiles) {

    // add your files to the regular upload form
    var target = $(target);
    var uploadFormData = new FormData($("#upload")[0]); 
    if(droppedFiles.length > 0) { // checks if any files were dropped
        uploadFormData.append("file", droppedFiles[0]);
        uploadFormData.append("dpto", target.attr( 'mi_dpto' ));
        uploadFormData.append("clave_asig", target.attr( 'mi_clave_asig' ));
        uploadFormData.append("tipo_doc", target.attr( 'mi_tipo_doc' ));
        uploadFormData.append("extensiones_id", target.attr( 'mi_extensiones_id' ));
        uploadFormData.append("nivel", target.attr( 'mi_nivel' ));

    }

    target.parent().children('#loading').toggle();

    // the final ajax call
    $.ajax({
        url : "upload.php", // use your target
        type : "POST",
        data : uploadFormData,
        cache : false,
        contentType : false,
        processData : false,
        success : function(ret) {
            target.parent().children('#loading').toggle();
            if ("ok" in ret) {
                console.log(ret['ok']);
                target.parent().removeClass('noExiste').addClass('existe');
                target.parent().html(ret['ok']);
            } else {
                alert(ret['error']); // show response from the php script.
            }
        }
    });
}

function dropAreaHover(e) {
    e.stopPropagation();
    e.preventDefault();
    if (e.type == "dragenter") {
      //$id("main").className = "dropping";
    } else if (e.type == "dragleave") {
      //$id("main").className = "waitdrop";
    }
}

function dropAreaDrop(e) {
    e.stopPropagation();
    e.preventDefault();
    var basename = e.target.id;
    var files = e.originalEvent.dataTransfer.files;
    processFileUpload(e.target, files);
    // forward the file object to your ajax upload method
    return false;
}

function noDrop(e) {
    e.stopPropagation();
    e.preventDefault();
    return false;
}


$(document).ready( function() {

    $('.dropArea').bind('dragenter', dropAreaHover);
    $('.dropArea').bind('dragover', dropAreaHover);
    $('.dropArea').bind('dragleave', dropAreaHover);
    $('.dropArea').bind('drop', dropAreaDrop);

    //$('#container').bind('dragenter', noDrop);
    //$('#container').bind('drop', noDrop);

});
