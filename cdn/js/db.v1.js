class db {
  constructor (action) {
    this.values_    = {};
    this.url_     = "/admin/php/delete.php";    
    this.where_   = '';
    this.action_  = action;
    this.table_   = '';
    this.leftJoinT_ = '';

    this.type_ = action == 'delete' || action == 'update' || action == 'create' ? 'POST' : 'GET'; 

  }
  url (str) {    
    this.url_ =str;
    return this // Aqui!
  }
  values (obj) {
    this.values_ = obj;    
    return this; // Aqui!
  }
  leftJoinT (a,b,c,d,e,f) {
    this.leftJoinT_ = [a,b,c,d,e,f];
    return this; // Aqui!
  }
  table (table) {
    this.table_ = table;
    return this // Aqui!
  }
  where(args){
    this.where_ = args;
    return this;
  }
  exe(call,dt=''){
    let datatype_ = 'html';
    if(dt!='')
      datatype_ = dt;
    if(this.action_ == 'create')
      this.where_ = '';

    $.ajax({
      url : this.url_,
      type : this.type_,
      dataType: datatype_,
      data : {
        action    : this.action_,
        table     : this.table_,
        leftjoint : this.leftJoinT_,
        where     : this.where_,
        values    : this.values_,
        datatype  : datatype_
      },
      beforeSend : function(){
        console.log('before');
      }
    })
    .done(function(msg){      
      call(msg);
    })
    .fail(function(jqXHR, textStatus, msg){
      alert(msg);
    }); 
  }
}