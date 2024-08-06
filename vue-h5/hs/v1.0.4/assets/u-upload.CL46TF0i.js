import{bT as e,bU as t,bV as a,a0 as s,a1 as i,a2 as l,al as o,a4 as u,b1 as d,bW as r,bK as n,x as p,y as c,z as m,A as f,H as h,D as y,F as _,E as g,a9 as b,I as v,J as w,G as k,bo as x,a8 as z,B as C,N as I,O as j,C as F}from"./index-DxUBkcQO.js";import{_ as S}from"./u-icon.BCEdui8G.js";import{r as T}from"./uni-app.es.WyEFFhdr.js";import{_ as R}from"./u-loading-icon.DIzsuW0j.js";import{_ as B}from"./_plugin-vue_export-helper.BCo6x5W8.js";function D(e,t){return["[object Object]","[object File]"].includes(Object.prototype.toString.call(e))?Object.keys(e).reduce(((a,s)=>(t.includes(s)||(a[s]=e[s]),a)),{}):{}}function O(e){return e.tempFiles.map((e=>({...D(e,["path"]),url:e.path,size:e.size,name:e.name,type:e.type})))}function P({accept:s,multiple:i,capture:l,compressed:o,maxDuration:u,sizeType:d,camera:r,maxCount:n}){return new Promise(((p,c)=>{switch(s){case"image":a({count:i?Math.min(n,9):1,sourceType:l,sizeType:d,success:e=>p(function(e){return e.tempFiles.map((e=>({...D(e,["path"]),type:"image",url:e.path,thumb:e.path,size:e.size,name:e.name})))}(e)),fail:c});break;case"video":t({sourceType:l,compressed:o,maxDuration:u,camera:r,success:e=>p(function(e){return[{...D(e,["tempFilePath","thumbTempFilePath","errMsg"]),type:"video",url:e.tempFilePath,thumb:e.thumbTempFilePath,size:e.size,name:e.name}]}(e)),fail:c});break;case"file":e({count:i?n:1,type:s,success:e=>p(O(e)),fail:c});break;default:e({count:i?n:1,type:"all",success:e=>p(O(e)),fail:c})}}))}const $=B({name:"u-upload",mixins:[i,l,{watch:{accept:{immediate:!0,handler(e){}}}},{props:{accept:{type:String,default:()=>s.upload.accept},capture:{type:[String,Array],default:()=>s.upload.capture},compressed:{type:Boolean,default:()=>s.upload.compressed},camera:{type:String,default:()=>s.upload.camera},maxDuration:{type:Number,default:()=>s.upload.maxDuration},uploadIcon:{type:String,default:()=>s.upload.uploadIcon},uploadIconColor:{type:String,default:()=>s.upload.uploadIconColor},useBeforeRead:{type:Boolean,default:()=>s.upload.useBeforeRead},afterRead:{type:Function,default:null},beforeRead:{type:Function,default:null},previewFullImage:{type:Boolean,default:()=>s.upload.previewFullImage},maxCount:{type:[String,Number],default:()=>s.upload.maxCount},disabled:{type:Boolean,default:()=>s.upload.disabled},imageMode:{type:String,default:()=>s.upload.imageMode},name:{type:String,default:()=>s.upload.name},sizeType:{type:Array,default:()=>s.upload.sizeType},multiple:{type:Boolean,default:()=>s.upload.multiple},deletable:{type:Boolean,default:()=>s.upload.deletable},maxSize:{type:[String,Number],default:()=>s.upload.maxSize},fileList:{type:Array,default:()=>s.upload.fileList},uploadText:{type:String,default:()=>s.upload.uploadText},width:{type:[String,Number],default:()=>s.upload.width},height:{type:[String,Number],default:()=>s.upload.height},previewImage:{type:Boolean,default:()=>s.upload.previewImage}}}],data:()=>({lists:[],isInCount:!0}),watch:{fileList:{handler(){this.formatFileList()},immediate:!0,deep:!0}},emits:["error","beforeRead","oversize","afterRead","delete","clickPreview"],methods:{addUnit:o,addStyle:u,formatFileList(){const{fileList:e=[],maxCount:t}=this,a=e.map((e=>Object.assign(Object.assign({},e),{isImage:"image"===this.accept||d.image(e.url||e.thumb),isVideo:"video"===this.accept||d.video(e.url||e.thumb),deletable:"boolean"==typeof e.deletable?e.deletable:this.deletable})));this.lists=a,this.isInCount=a.length<t},chooseFile(){const{maxCount:e,multiple:t,lists:a,disabled:s}=this;if(s)return;let i;try{i=d.array(this.capture)?this.capture:this.capture.split(",")}catch(l){i=[]}P(Object.assign({accept:this.accept,multiple:this.multiple,capture:i,compressed:this.compressed,maxDuration:this.maxDuration,sizeType:this.sizeType,camera:this.camera},{maxCount:e-a.length})).then((e=>{this.onBeforeRead(t?e:e[0])})).catch((e=>{this.$emit("error",e)}))},onBeforeRead(e){const{beforeRead:t,useBeforeRead:a}=this;let s=!0;d.func(t)&&(s=t(e,this.getDetail())),a&&(s=new Promise(((t,a)=>{this.$emit("beforeRead",Object.assign(Object.assign({file:e},this.getDetail()),{callback:e=>{e?t():a()}}))}))),s&&(d.promise(s)?s.then((t=>this.onAfterRead(t||e))):this.onAfterRead(e))},getDetail(e){return{name:this.name,index:null==e?this.fileList.length:e}},onAfterRead(e){const{maxSize:t,afterRead:a}=this;(Array.isArray(e)?e.some((e=>e.size>t)):e.size>t)?this.$emit("oversize",Object.assign({file:e},this.getDetail())):("function"==typeof a&&a(e,this.getDetail()),this.$emit("afterRead",Object.assign({file:e},this.getDetail())))},deleteItem(e){this.$emit("delete",Object.assign(Object.assign({},this.getDetail(e)),{file:this.fileList[e]}))},onPreviewImage(e){e.isImage&&this.previewFullImage&&r({urls:this.lists.filter((e=>"image"===this.accept||d.image(e.url||e.thumb))).map((e=>e.url||e.thumb)),current:e.url||e.thumb,fail(){n("预览图片失败")}})},onPreviewVideo(e){this.data.previewFullImage&&(e.currentTarget.dataset,this.data)},onClickPreview(e){const{index:t}=e.currentTarget.dataset,a=this.data.lists[t];if(this.data.previewFullImage){if("video"===a.type)this.onPreviewVideo(e);this.$emit("clickPreview",Object.assign(Object.assign({},a),this.getDetail(t)))}}}},[["render",function(e,t,a,s,i,l){const o=I,u=T(p("u-icon"),S),d=j,r=F,n=T(p("u-loading-icon"),R);return c(),m(r,{class:"u-upload",style:b([l.addStyle(e.customStyle)])},{default:f((()=>[h(r,{class:"u-upload__wrap"},{default:f((()=>[e.previewImage?(c(!0),y(_,{key:0},g(i.lists,((t,a)=>(c(),m(r,{class:"u-upload__wrap__preview",key:a},{default:f((()=>[t.isImage||t.type&&"image"===t.type?(c(),m(o,{key:0,src:t.thumb||t.url,mode:e.imageMode,class:"u-upload__wrap__preview__image",onClick:e=>l.onPreviewImage(t),style:b([{width:l.addUnit(e.width),height:l.addUnit(e.height)}])},null,8,["src","mode","onClick","style"])):(c(),m(r,{key:1,class:"u-upload__wrap__preview__other",onClick:e=>l.onClickPreview(e,t)},{default:f((()=>[h(u,{color:"#80CBF9",size:"26",name:t.isVideo||t.type&&"video"===t.type?"movie":"folder"},null,8,["name"]),h(d,{class:"u-upload__wrap__preview__other__text"},{default:f((()=>[v(w(t.isVideo||t.type&&"video"===t.type?"视频":"文件"),1)])),_:2},1024)])),_:2},1032,["onClick"])),"uploading"===t.status||"failed"===t.status?(c(),m(r,{key:2,class:"u-upload__status"},{default:f((()=>[h(r,{class:"u-upload__status__icon"},{default:f((()=>["failed"===t.status?(c(),m(u,{key:0,name:"close-circle",color:"#ffffff",size:"25"})):(c(),m(n,{key:1,size:"22",mode:"circle",color:"#ffffff"}))])),_:2},1024),t.message?(c(),m(d,{key:0,class:"u-upload__status__message"},{default:f((()=>[v(w(t.message),1)])),_:2},1024)):k("",!0)])),_:2},1024)):k("",!0),"uploading"!==t.status&&(e.deletable||t.deletable)?(c(),m(r,{key:3,class:"u-upload__deletable",onClick:x((e=>l.deleteItem(a)),["stop"])},{default:f((()=>[h(r,{class:"u-upload__deletable__icon"},{default:f((()=>[h(u,{name:"close",color:"#ffffff",size:"10"})])),_:1})])),_:2},1032,["onClick"])):k("",!0),"success"===t.status?(c(),m(r,{key:4,class:"u-upload__success"},{default:f((()=>[h(r,{class:"u-upload__success__icon"},{default:f((()=>[h(u,{name:"checkmark",color:"#ffffff",size:"12"})])),_:1})])),_:1})):k("",!0)])),_:2},1024)))),128)):k("",!0),i.isInCount?(c(),y(_,{key:1},[e.$slots.default||e.$slots.$default?(c(),m(r,{key:0,style:{width:"100%",height:"100%"},onClick:l.chooseFile},{default:f((()=>[z(e.$slots,"default",{},void 0,!0)])),_:3},8,["onClick"])):(c(),m(r,{key:1,class:C(["u-upload__button",[e.disabled&&"u-upload__button--disabled"]]),"hover-class":e.disabled?"":"u-upload__button--hover","hover-stay-time":"150",onClick:l.chooseFile,style:b([{width:l.addUnit(e.width),height:l.addUnit(e.height)}])},{default:f((()=>[h(u,{name:e.uploadIcon,size:"26",color:e.uploadIconColor},null,8,["name","color"]),e.uploadText?(c(),m(d,{key:0,class:"u-upload__button__text"},{default:f((()=>[v(w(e.uploadText),1)])),_:1})):k("",!0)])),_:1},8,["hover-class","onClick","class","style"]))],64)):k("",!0)])),_:3})])),_:3},8,["style"])}],["__scopeId","data-v-7dc8a97f"]]);export{$ as _};
