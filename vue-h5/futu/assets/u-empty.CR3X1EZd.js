import{_ as t}from"./u-icon.D4qWymo2.js";import{a1 as e,a2 as s,a3 as a,al as o,a4 as i,a5 as r,x as n,y as m,z as l,A as p,aa as d,H as y,I as u,J as c,a9 as f,G as h,N as S,O as g,C as x}from"./index-CM0aLgVp.js";import{r as _}from"./uni-app.es.DvFBep3n.js";import{_ as w}from"./_plugin-vue_export-helper.BCo6x5W8.js";const z=w({name:"u-empty",mixins:[s,a,{props:{icon:{type:String,default:()=>e.empty.icon},text:{type:String,default:()=>e.empty.text},textColor:{type:String,default:()=>e.empty.textColor},textSize:{type:[String,Number],default:()=>e.empty.textSize},iconColor:{type:String,default:()=>e.empty.iconColor},iconSize:{type:[String,Number],default:()=>e.empty.iconSize},mode:{type:String,default:()=>e.empty.mode},width:{type:[String,Number],default:()=>e.empty.width},height:{type:[String,Number],default:()=>e.empty.height},show:{type:Boolean,default:()=>e.empty.show},marginTop:{type:[String,Number],default:()=>e.empty.marginTop}}}],data:()=>({icons:{car:"购物车为空",page:"页面不存在",search:"没有搜索结果",address:"没有收货地址",wifi:"没有WiFi",order:"订单为空",coupon:"没有优惠券",favor:"暂无收藏",permission:"无权限",history:"无历史记录",news:"无新闻列表",message:"消息列表为空",list:"列表为空",data:"数据为空",comment:"暂无评论"}}),computed:{emptyStyle(){const t={};return t.marginTop=o(this.marginTop),i(r(this.customStyle),t)},textStyle(){const t={};return t.color=this.textColor,t.fontSize=o(this.textSize),t},isSrc(){return this.icon.indexOf("/")>=0}},methods:{addUnit:o}},[["render",function(e,s,a,o,i,r){const w=_(n("u-icon"),t),z=S,C=g,N=x;return e.show?(m(),l(N,{key:0,class:"u-empty",style:d([r.emptyStyle])},{default:p((()=>[r.isSrc?(m(),l(z,{key:1,style:d({width:r.addUnit(e.width),height:r.addUnit(e.height)}),src:e.icon,mode:"widthFix"},null,8,["style","src"])):(m(),l(w,{key:0,name:"message"===e.mode?"chat":`empty-${e.mode}`,size:e.iconSize,color:e.iconColor,"margin-top":"14"},null,8,["name","size","color"])),y(C,{class:"u-empty__text",style:d([r.textStyle])},{default:p((()=>[u(c(e.text?e.text:i.icons[e.mode]),1)])),_:1},8,["style"]),e.$slots.default||e.$slots.$default?(m(),l(N,{key:2,class:"u-empty__wrap"},{default:p((()=>[f(e.$slots,"default",{},void 0,!0)])),_:3})):h("",!0)])),_:3},8,["style"])):h("",!0)}],["__scopeId","data-v-b36c8aec"]]);export{z as _};
