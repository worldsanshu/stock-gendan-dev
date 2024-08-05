import{aB as t,y as e,D as i,z as n,aa as o,G as h,aC as a}from"./index-MDKEdHpS.js";import{_ as r}from"./_plugin-vue_export-helper.BCo6x5W8.js";const s=r({name:"jp-verification-literalness",props:{securityCode:{type:String,default:""},codeLength:{type:Number,default:4},contentWidth:{type:Number,default:80},contentHeight:{type:Number,default:40},lineLength:{type:Number,default:8},backgroundColor:{type:String,default:"rgb(238,226,224)"},lineColorList:{type:Array,default:()=>["rgba(238,0,0,.5)","rgba(0, 170, 255,.5)","rgba(0, 170, 0,.5)","rgba(0, 0, 0,.5)","rgba(153, 146, 255,.5)"]},colorList:{type:Array,default:()=>["rgb(238,0,0)","rgb(0, 170, 255)","rgb(0, 170, 0)","rgb(0, 0, 0)","rgb(153, 146, 255)"]}},computed:{canvasId(){return`lime-signature${this._.uid}`}},data:()=>({identifyCode:""}),watch:{securityCode(){this.drawPic()}},methods:{verification(t){return this.identifyCode==t},randomNum:(t,e)=>Math.floor(Math.random()*(e-t)+t),getcheckCode(){let t="";const e=this.codeLength,i=[1,2,3,4,5,6,7,8,9,"A","B","C","D","E","F","G","H","I","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y","Z"];for(let n=0;n<e;n++){t+=i[Math.floor(34*Math.random())]}this.identifyCode=t},drawTap(){this.securityCode?this.$emit("getCode"):this.drawPic()},drawPic(){this.securityCode?this.identifyCode=this.securityCode:this.getcheckCode();let e=t(this.canvasId,this);e.setTextBaseline("bottom"),e.setFillStyle(this.backgroundColor),e.fillRect(0,0,this.contentWidth,this.contentHeight);for(let t=0;t<this.identifyCode.length;t++)this.drawText(e,this.identifyCode[t],t);this.drawLine(e),e.draw()},drawText(t,e,i){let n=Math.floor(Math.random()*this.colorList.length);t.setFillStyle(this.colorList[n]);let o=Math.trunc(this.contentWidth/this.identifyCode.length);console.log(o),t.setFontSize(this.randomNum(o,o)+"px SimHei");let h=i*(this.contentWidth/(this.identifyCode.length+1))+Math.trunc(o/2),a=this.randomNum(o,this.contentHeight-5);var r=this.randomNum(-10,10);t.translate(h,a),t.rotate(r*Math.PI/180),t.fillText(e,0,0),t.rotate(-r*Math.PI/180),t.translate(-h,-a)},drawLine(t){for(let e=0;e<this.lineLength;e++){let e=Math.floor(Math.random()*this.lineColorList.length);t.setStrokeStyle(this.lineColorList[e]),t.beginPath();let i=this.randomNum(0,this.contentWidth),n=this.randomNum(0,this.contentHeight),o=this.randomNum(0,this.contentWidth),h=this.randomNum(0,this.contentHeight);t.moveTo(i,n),t.lineTo(o,h),t.stroke()}}},mounted(){this.drawPic()}},[["render",function(t,r,s,d,l,c){const g=a;return e(),i("div",{style:o({height:s.contentHeight+"px",width:s.contentWidth+"px"})},[c.canvasId?(e(),n(g,{key:0,onClick:c.drawTap,id:c.canvasId,canvasId:c.canvasId,width:s.contentWidth,height:s.contentHeight,style:o({height:s.contentHeight+"px",width:s.contentWidth+"px"})},null,8,["onClick","id","canvasId","width","height","style"])):h("",!0)],4)}]]);export{s as _};
