//PARA QUE SE MUESTRE EL MENU DE LOS ROLES PHP
var tableRoles;
var divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function(){ //EVENTO PARA CARGAR EL DATATABLE

	tableRoles = $('#tableRoles').dataTable( {//INICIO DE DATATABLE IGULANDO LOS PARAMETROS HACIENDO REFERENCIA AL ID DE LA TABLA
		"aProcessing":true,
		"aServerSide":true,
        "language": {
        	"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"//CONFIGURACION DEL IDIOMA
        },
        "ajax":{
            "url": " "+base_url+"/Roles/getRoles",//CON LA URL RAIZ DIRECIONAMOS AL METODO getRoles DEL CONTROLADOR Roles
            "dataSrc":""
        },
        "columns":[
            {"data":"idrol"},
            {"data":"nombrerol"},
            {"data":"descripcion"},
            {"data":"status"},
            {"data":"options"}
        ],
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]//LO ORDENA DE FORMA DESCENDENTE REFERENTA EL ID PRIMARA COLUMNA 
    });

    //NUEVO ROL CAPTURANDO EL EVENTO CLICK DEL BOTON NUEVO ROL DEL FORMULARIO
    var formRol = document.querySelector("#formRol");//ID DEL FORMULARIO DE NUEVO ROL 
    formRol.onsubmit = function(e) {
        e.preventDefault();//EVITA QUE SE RECARGUE LA PAGINA AL ENVIAR EL FORMULARIO 
        //CAPTURAMOS LOS VALORES DEL FORMULARIO
        var intIdRol = document.querySelector('#idRol').value;
        var strNombre = document.querySelector('#txtNombre').value;
        var strDescripcion = document.querySelector('#txtDescripcion').value;
        var intStatus = document.querySelector('#listStatus').value;        
        if(strNombre == '' || strDescripcion == '' || intStatus == '')//VALIDACION DE CAMPOS VACIOS
        {
            swal("Atención", "Todos los campos son obligatorios." , "error");
            return false;
        }
        divLoading.style.display = "flex";
        //DETECTAMOS EL NAVEGADOR Y LA VERSION DEL NAVEGADOR PARA REALIZAR LA CONEXION AJAX
        //REALIZAMOS LA PETICION AJAX AL SERVIDOR
        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        //LA URL RAIZ DIRECIONAMOS AL METODO getRoles DEL CONTROLADOR Roles
        var ajaxUrl = base_url+'/Roles/setRol';
        //HACEMOS LA REFERENCIA AL FORMULARIO formRol 
        var formData = new FormData(formRol);
        //HACEMOS EL ENVIO DE DATOS POR MEDIO DE AJAX
        //REFIRIENDODOS A REQUEST 
        //Y EL METODO POR DONDE SE ENVIARAN LOS DATOS 
        //A LA URL AJAXURL 
        request.open("POST",ajaxUrl,true);
        //ENVIAMOS LA INFORMACION DEL FORMULARIO
        request.send(formData);
        request.onreadystatechange = function(){//HACEMOS EL REQUEST DONDE DETONAMOS LA FUNCION
           if(request.readyState == 4 && request.status == 200){
                //CAPTURAMOS EL MENSAJE DE LA RESPUESTA DEL SERVIDOR ESTE MENSAJE LO PODEMOS VER EN INSPECCIONAR-RED LA URL DONDE ESTAMOS ENVIANDO LOS DATOS
                var objData = JSON.parse(request.responseText);//CON PARSE CONVERTIMOS EL MENSAJE EN UN OBJETO
                if(objData.status)//SI EL STATUS ES TRUE ENTONCES SE REALIZO CORRECTAMENTE LA OPERACION
                {
                    $('#modalFormRol').modal("hide");//CERRAMOS EL MODAL
                    formRol.reset();//RESETEAMOS EL FORMULARIO
                    swal("Roles de usuario", objData.msg ,"success");//MOSTRAMOS EL MENSAJE QUE TRAIGA EL OBJETO MSG
                    tableRoles.api().ajax.reload(function () {//RECARGAMOS LA TABLA
                        fntEditRol();
                    });
                }else{
                    swal("Error", objData.msg , "error");//MOSTRAMOS LO QUE CONTENGA EL OBJETO MSG
                }              
            } 
            divLoading.style.display = "none";
            return false;
        }

        
    }

});

$('#tableRoles').DataTable();

function openModal(){

    document.querySelector('#idRol').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Rol";
    document.querySelector("#formRol").reset();
	$('#modalFormRol').modal('show');
}

window.addEventListener('load', function() {
    /*fntEditRol();
    fntDelRol();
    fntPermisos();*/
}, false);

function fntEditRol(idrol){//EVENTO CLICK A CADA UNO DE LOS BOTONES EDITAR
    document.querySelector('#titleModal').innerHTML ="Actualizar Rol";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";

    var idrol = idrol;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl  = base_url+'/Roles/getRol/'+idrol;
    request.open("GET",ajaxUrl ,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            
            var objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                document.querySelector("#idRol").value = objData.data.idrol;
                document.querySelector("#txtNombre").value = objData.data.nombrerol;
                document.querySelector("#txtDescripcion").value = objData.data.descripcion;

                if(objData.data.status == 1)
                {
                    var optionSelect = '<option value="1" selected class="notBlock">Activo</option>';
                }else{
                    var optionSelect = '<option value="2" selected class="notBlock">Inactivo</option>';
                }
                var htmlSelect = `${optionSelect}
                                  <option value="1">Activo</option>
                                  <option value="2">Inactivo</option>
                                `;
                document.querySelector("#listStatus").innerHTML = htmlSelect;
                $('#modalFormRol').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }

}

function fntDelRol(idrol){
    var idrol = idrol;
    swal({
        title: "Eliminar Rol",
        text: "¿Realmente quiere eliminar el Rol?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        
        if (isConfirm)//SI ES TRUE EL USUARIO PRESIONO EL BOTON SI 
        {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url+'/Roles/delRol/';
            var strData = "idrol="+idrol;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    var objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        swal("Eliminar!", objData.msg , "success");
                        tableRoles.api().ajax.reload(function(){
                            fntEditRol();
                            fntDelRol();
                            fntPermisos();
                        });
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }

    });
}

function fntPermisos(idrol){
    var idrol = idrol;//CAPTURAMOS EL ID DEL ROL
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');//DEACUERDO AL NAVEGADOR QUE ESTAMOS USANDO CREAMOS UN OBJETO REQUEST
    var ajaxUrl = base_url+'/Permisos/getPermisosRol/'+idrol;//LA RURA RAIZ DE NUESTRO SISTEMA WEB + LA RUTA DONDE ESTA LA FUNCION QUE NOS TRAE CON EL ID DEL ROL
    request.open("GET",ajaxUrl,true);//ADRIMOS LA CONEXION DONDE LE IDICAMOS QUE LA PERICION SERA DE TIPO GET QUE SERA ENVIADO ATRAVEZ DE ajaxUrl 
    request.send();//ENVIAMOS LA PETICION

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            //VAMOS HACER REFERENCIA AL ELEMENTO DONDE VAMOS A MOSTRAR LA RESPUESTA QUE ESTAMOOS RECIBIENDO DEL LA FUNCION getPermisosRol VARIABLE REQUEST $html QUE CORRESPONDE AL MODAL
            document.querySelector('#contentAjax').innerHTML = request.responseText;
            $('.modalPermisos').modal('show');//MOSTRAMOS EL MODAL
            document.querySelector('#formPermisos').addEventListener('submit',fntSavePermisos,false);//CON DEL ID DEL FORMULARIO DEL MODAL LE AGREGAMOS EL EVENTO SUBMIT DONDE SE EJECUTARA LA FUNCION fntSavePermisos  
        }
    }
}

function fntSavePermisos(evnet){
    evnet.preventDefault();
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Permisos/setPermisos'; 
    var formElement = document.querySelector("#formPermisos");
    var formData = new FormData(formElement);
    request.open("POST",ajaxUrl,true);
    request.send(formData);

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                swal("Permisos de usuario", objData.msg ,"success");
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
    
}