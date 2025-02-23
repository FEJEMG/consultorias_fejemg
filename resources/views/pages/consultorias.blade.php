@extends('template') @section('content')
<link
	href="{{ URL::asset('js/jquery-easyui-1.4.2/themes/bootstrap/easyui.css') }}"
	rel="stylesheet" type="text/css">
<link href="{{ URL::asset('js/jquery-easyui-1.4.2/themes/icon.css') }}"
	rel="stylesheet" type="text/css">
<script type="text/javascript"
	src="{{ URL::asset('js/jquery-easyui-1.4.2/jquery.min.js') }}"></script>
	<script type="text/javascript"
	src="{{ URL::asset('https://cdn.rawgit.com/dinbror/bpopup/master/jquery.bpopup.min.js') }}"></script>

<div id="popupOla" style="display:none" class="container">
	<p>Olá, Empresário Júnior! <br />
	   <br />
	   Seja bem vindo à versão beta do <strong>Consultorias</strong>.<br />
	   Por se tratar de uma versão de teste, algumas funcionalidades ainda não estão disponíveis e erros podem acontecer. <br />
	   Devido a isto, <strong>precisamos de sua ajuda</strong>. Caso encontre algum erro, uma EJ faltando ou um serviço incorreto, não deixe de entrar em
	   contato enviando um email para  <a href="mailto:ti@fejemg.org.br">ti@fejemg.org.br</a>.<br />
	   <br />
	   <strong>Com a sua ajuda, faremos este o melhor site de consultoria empresarial do mundo!</strong><br />
	   <br />
	   Do seu time de TI favorito, <a  target = "_blank" href="https://www.facebook.com/marcelodive?_rdr=p">Marcelo Rodrigues</a>.
	</p>
</div>

<h1>Pesquise</h1>
<hr></hr>
<br>
{!! Form::open() !!}
<input type="hidden" name="_token" value="{{{ csrf_token() }}}">
<div class="container row">
	<div class="col-md-3" style="margin:20px;">
		<select id="cities" class="easyui-combobox form-control" name="city_selected"> 
				<option value="Todas as Cidades">Todas as Cidades</option>
			@foreach($cities as $city)
				<option value="{{ $city->city_name }}">{{ $city->city_name }}</option>
			@endforeach 
		</select>	
	</div>
	<div class="col-md-3" style="margin:20px;">  
		<select id="fields" class="easyui-combobox form-control" name="field_selected"> 
		</select>	
	</div>
	<div class="col-md-3" style="margin:20px;">  
		<select id="services" class="easyui-combobox form-control" name="service_selected"> 
		</select>	
	</div>
	<div class="col-md-1" style="margin:20px;">  
		<input class="btn btn-default" type="submit" value="Pesquisar">
	</div>	
</div>
{!! Form::close() !!}

<hr></hr> 

<div id="fieldsData" data-load='{{ $jsonifiedCities }}'></div> 
<div id="servicesData" data-load='{{ $jsonifiedJEs }}'></div>

<script> 
	$(document).ready(function(){	
		ordenarCidades();
		//$('#popup').bPopup();
	
		changeFields();

		//Primeira carregamento dos serviços.	
		var load = $('#servicesData').data('load');
		var lists = "";
	  //lists = lists + "<option value=\"Todos os Serviços\">Todos os Serviços</option>";
		for(var field in load){
			load[field].forEach(function(entry){
        		lists = lists + "<option value=\""+entry.name+"\">"+entry.name+"</option>";
		    });
		}
		$('#services').html(lists);
		ordenarServices();
		
		
	  $('#cities').change(function(){
		load = $('#fieldsData').data('load');
	    var value = $(this).val();
	    lists = "";
	    if (value == "Todas as Cidades"){
	    	  changeFields();
	      }else{
	      	for(var city in load){
	      		if(value == city){
	        		load[city].forEach(function(entry){
	          		lists = lists + "<option value=\""+entry.name+"\">"+entry.name+"</option>";
	        		});
	      		}
			}
			$('#fields').html(lists);
	    }
	    changeServices();
	  });
	  

	  $('#fields').change(function(){
		  
	  changeServices();
	});

	function changeServices(){
		var city =  $('#cities').val();
  		var ramo =  $('#fields').val();
  		//var restRequest = "http://localhost:8888/consultorias/"+city+"\/"+ramo; 
  		//var restRequest = "http://fejemg.org.br/consultorias_fejemg/public/consultorias"+"\/"+city+"\/"+ramo; 
		var restRequest = "{{asset('consultorias')}}"+"\/"+city+"\/"+ramo; 
  		$.ajax({
    			url: restRequest
			}).then(function(data) {
       			$('#services').html(data);
				 });
		ordenarServices();
  	};

  	function changeFields(){
		var load = $('#fieldsData').data('load');
		var lists = "";				
		var names = [];
		var uniqueNames = [];
		lists = lists + "<option value=\"Todos os Ramos\">Todos os Ramos</option>";
		for(var city in load){
			load[city].forEach(function(entry){
				names.push(entry.name);        		
		    });
		}

		$.each(names, function(i, el){
		    if($.inArray(el, uniqueNames) === -1) {
		    	 lists = lists + "<option value=\""+el+"\">"+el+"</option>";
		   		 uniqueNames.push(el);
		    }
		});	
		$('#fields').html(lists);
	ordenarFields();
	}

	function ordenarFields(){   
        $("#fields").html($("option", $("#fields")).sort(function(a,b){
	    if(a.text == "Todos os Ramos") return -1;
	    else if (b.text == "Todos os Ramos") return 1;
            return a.text == b.text  ? 0 : a.text < b.text ? -1 : 1;
        }));
    }
    function ordenarServices(){   
        $("#services").html($("option", $("#services")).sort(function(a,b){
            return a.text == b.text  ? 0 : a.text < b.text ? -1 : 1;
        }));
    }
     function ordenarCidades(){   
        $("#cities").html($("option", $("#cities")).sort(function(a,b){
	    if(a.text == "Todas as Cidades") return -1;
	    else if (b.text == "Todas as Cidades") return 1;
            return a.text == b.text  ? 0 : a.text < b.text ? -1 : 1;
        }));
    }
});
</script>
@stop
