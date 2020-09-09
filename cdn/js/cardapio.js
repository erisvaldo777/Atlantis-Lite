var etapa_atual=0;
var cardapio=[];
var layout_category=[];
var qtd_sabor=0;

var step=[];







class Components { 

	constructor(nome,tipo) {
		this.nome = nome;
		this.tipo=tipo==null?this.tipo:tipo;
	}

	getList(args) {
		console.log('::getList->args',args);
		var html='';
		
		let items=cardapio.table[0][args.table];
		let target=args.table_items;

		console.log('target',target);

		$.each(target,function(k,v){			
			console.log('args.table',args.table);
			let item=[];
			let price='';
			if(args.table!=undefined){

				item=singularis.filter(cardapio.table[0][args.table],{'id':v.id})[0];

				console.log('item',item);
			}else{
				item=v;
			}

			if(args.show_price==true){
				price='<span class="count-number float-right">'+
				'<span class="price">R$'+v.price+'</span>'+
				'</span>';
			}

			if(item!=undefined){

				html+= '<div class="p-3 border-bottom">'+
				price+
				'<div class="media">'+
				'	<div class="media-body">'+
				'		<div class="custom-control custom-radio">'+
				'			<input type="radio" id="" name="customRadio" class="custom-control-input"/>'+
				'			<label class="custom-control-label title" for="">'+item.title+'</label>'+
				'		</div>'+
				'	</div>'+
				'</div>'+
				'</div>'
			}
			
		});
		return html;
	}
	getConteudo(){

	}
	getPreCheckout(args){
		console.log('getPreCheckout',args);
		var pedido=[{"pedido":"Pastel P","items":[{"item":"Carne de sol","price":"3,00"},{"item":"Qualho","price":"4,00"},{"item":"Cream Cheese","price":"3,00"},		{"item":"Milho","price":"34,00"},		{"item":"Azeitona","price":"32,00"},		{"item":"Passas","price":"23,00"},		{"item":"Barbecue","price":"13,00"},		{"item":"Coca 1L","price":"3,00"},		{"item":"Goiaba","price":"4,50"}		]	},	{"pedido":"PIZZA G","items":	[	{"item":"Carne de sol","price":"3,00"},	{"item":"Qualho","price":"4,00"},	{"item":"Cream Cheese","price":"3,00"},	{"item":"Milho","price":"34,00"},	{"item":"Azeitona","price":"32,00"},	{"item":"Passas","price":"23,00"},	{"item":"Barbecue","price":"13,00"},	{"item":"Coca 1L","price":"3,00"},	{"item":"Goiaba","price":"4,50"}	]}];
		var html='';
		$.each(pedido,function(k,v){
			html+='<b>PEDIDO: '+v.pedido+'</b><br/>';
			$.each(v.items,function(k,v){

				html+= '<div class="border-bottom">'+
				'<span class="count-number float-right">'+
				'<span>R$'+v.price+'</span>'+
				'</span>'+
				'<div class="media">'+
				'	<div class="media-body">'+
				'		<div class="">'+				
				'			<label class="" for="">'+v.item+'</label>'+
				'		</div>'+
				'	</div>'+
				'</div>'+
				'</div>'

				
			});
			html+='<b>SUBTOTAL: R$12,33</b><hr>';
		});
		console.log('args.configs[0].button',args.configs[0].btConfirm[0].button);
		$('.modal-footer').html(args.configs[0].btConfirm[0].button);
		return '<div>'+html+'</div>';
	}
}


class Gato extends Components {
	falar() {
		console.log(this.nome + ' é um '+this.tipo);
	}
}

var cmp=new Gato('','');


var layoutFn=function(id_layout,args,args2){

	var layoutTemplate='';
	if(id_layout==1){/*PIZZA*/
		layoutTemplate= ' <div class="menu-list p-3 border-bottom" data-id_cardapio="'+args.id_cardapio+'" data-category="'+args2.category+'" data-tamanho="'+args.tamanho+'">'+
		'<span class="count-number float-right">'+
		'<span class="badge badge-warning">A partir de </span>'+
		'<span class="price">R$'+args.valor+'</span>'+
		'</span>'+
		'<div class="media">'+
		'<div class="media-body">'+
		'<h6 class="mb-1">'+args.title+'</h6>'+
		'<p class="text-gray mb-0">'+args.description+'</p>'+
		'</div>'+
		'</div>'+				
		'             </div>';

	}else{
		layoutTemplate='<div class="menu-list p-3 border-bottom">'+
		'<span class="count-number float-right">'+
		'<span class="price">R$'+args.valor+'</span>'+
		'</span>'+
		'<div class="media">'+
		'<div class="media-body">'+
		'<h6 class="mb-1">'+args.title+'</h6>'+
		'</div>'+
		'</div>'+
		'</div>';

	}
	return layoutTemplate;
}

$(function(){
	$.ajax({
		data: {},
		type : "post", 
		dataType: 'json',
		url: 'cliente/coco-bambu/cardapio/cardapio.json'
	}).done(function(data) { 

		window.cardapio=data;
		console.log('window.cardapio',data);
		$.each(data.cardapio,function(k,v){
			var html='<h5 class="mb-4 mt-3 col-md-12">'+v.description+'</h5>'+
			'<div class="col-md-12">'+
			'<div class="bg-white rounded border shadow-sm mb-4">';

			$.each(v.item,function(k1,v1){

				html+=layoutFn(v.id_layout,v1,v);


			})

			$('.cardapio').append('<div class="row">'+html+'</div></div></div>');
		})
	}).fail(function(jqXHR, textStatus, errorThrown) {
		/**/
		console.log(textStatus + ': ' + errorThrown);
	});      
})

//$('#modal-etapas').modal('show');

/*TUDO COMEÇA AQUI*/

/*QUANDO SELECIONA UM ITEM*/
$('body').on('click','.menu-list',function(){
	/*Reseta etapa_atual*/
	etapa_atual=0;
	$('#modal-etapas').modal('show');

	const{id_cardapio,category}=$(this).data();
	
	var cat=singularis.filter(cardapio.cardapio,{'category':category})[0];
	step=singularis.filter(cat.item,{'id_cardapio':id_cardapio})[0];
	
	var html="";

	
	$('#modal-etapas .box-header').html(cmp.getList(step.layouts[0]));
	$('#modal-etapas .modal-header').text(step.layouts[0].title);

});

/*SELECIONA A QUANTIDADE DE SABORES A ESCOLHER*/
$('body').on('click','.layOptQtd',function(){

	qtd_sabor=$(this).data('qtd_sabor');
	console.log('qtd_sabor',qtd_sabor);
})


/*SELECIONA A QUANTIDADE DE SABORES A ESCOLHER*/
$('body').on('change','#accordion .custom-control-input',function(){
	console.clear();
	console.log('checkbox selected',$("#accordion input[type=checkbox]:checked").length);
	if($("#accordion input[type=checkbox]:checked").length>qtd_sabor){
		console.log('limite atingido');
		$(this).prop('checked',false);
		return false;
	}
})



/*QUANDO SELECIONA UM ITEM*/


$('body').on('click','#btProximo',function(){
	console.clear();
	++etapa_atual;
	console.log('etapa_atual',etapa_atual);

	var thisMenu=step.layouts[etapa_atual];
	

	
	console.log('thisMenu.tite',thisMenu.title);

	let layout='';
	console.log('thisMenu.layout',thisMenu.layout);
	if(thisMenu.layout=='PRE_CHECKOUT'){
		layout=cmp.getPreCheckout(thisMenu);
	}else{
		layout=cmp.getList(thisMenu);
	}

				//console.groupEnd();				
				$('#modal-etapas .modal-header').text(thisMenu.title);

				$('#modal-etapas .box-header').html(layout);


				



			});
