import{_ as o}from"./u-line.DE4nbZs9.js";import{a1 as e,a2 as t,a3 as a,al as r,a5 as l,x as i,y as n,z as d,A as s,G as m,H as u,B as c,aa as g,I as h,J as p,C as f,O as x}from"./index-BKBmZM5g.js";import{r as y}from"./uni-app.es.CT9bmSuD.js";import{_ as S}from"./u-loading-icon.BDLNhgIp.js";import{_ as T}from"./_plugin-vue_export-helper.BCo6x5W8.js";const _=T({name:"u-loadmore",mixins:[t,a,{props:{status:{type:String,default:()=>e.loadmore.status},bgColor:{type:String,default:()=>e.loadmore.bgColor},icon:{type:Boolean,default:()=>e.loadmore.icon},fontSize:{type:[String,Number],default:()=>e.loadmore.fontSize},iconSize:{type:[String,Number],default:()=>e.loadmore.iconSize},color:{type:String,default:()=>e.loadmore.color},loadingIcon:{type:String,default:()=>e.loadmore.loadingIcon},loadmoreText:{type:String,default:()=>e.loadmore.loadmoreText},loadingText:{type:String,default:()=>e.loadmore.loadingText},nomoreText:{type:String,default:()=>e.loadmore.nomoreText},errorText:{type:String,default:()=>e.loadmore.errorText},isDot:{type:Boolean,default:()=>e.loadmore.isDot},iconColor:{type:String,default:()=>e.loadmore.iconColor},marginTop:{type:[String,Number],default:()=>e.loadmore.marginTop},marginBottom:{type:[String,Number],default:()=>e.loadmore.marginBottom},height:{type:[String,Number],default:()=>e.loadmore.height},line:{type:Boolean,default:()=>e.loadmore.line},lineColor:{type:String,default:()=>e.loadmore.lineColor},dashed:{type:Boolean,default:()=>e.loadmore.dashed}}}],data:()=>({dotText:"●"}),computed:{loadTextStyle(){return{color:this.color,fontSize:r(this.fontSize),lineHeight:r(this.fontSize),backgroundColor:this.bgColor}},showText(){let o="";return o="loadmore"==this.status?this.loadmoreText:"loading"==this.status?this.loadingText:"nomore"==this.status&&this.isDot?this.dotText:this.nomoreText,o},showTextV2(){switch(this.status){case"loadmore":return this.loadmoreText;case"loading":return this.loadingText;case"nomore":return this.isDot?this.dotText:this.nomoreText;case"error":return this.errorText}}},emits:["loadmore"],methods:{addStyle:l,addUnit:r,loadMore(){"loadmore"==this.status&&this.$emit("loadmore")}}},[["render",function(e,t,a,r,l,T){const _=y(i("u-line"),o),C=y(i("u-loading-icon"),S),b=f,z=x;return n(),d(b,{class:"u-loadmore",style:g([T.addStyle(e.customStyle),{backgroundColor:e.bgColor,marginBottom:T.addUnit(e.marginBottom),marginTop:T.addUnit(e.marginTop),height:T.addUnit(e.height)}])},{default:s((()=>[e.line?(n(),d(_,{key:0,length:"140rpx",color:e.lineColor,hairline:!1,dashed:e.dashed},null,8,["color","dashed"])):m("",!0),u(b,{class:c(["loadmore"==e.status||"nomore"==e.status?"u-more":"","u-loadmore__content"])},{default:s((()=>["loading"===e.status&&e.icon?(n(),d(b,{key:0,class:"u-loadmore__content__icon-wrap"},{default:s((()=>[u(C,{color:e.iconColor,size:e.iconSize,mode:e.loadingIcon},null,8,["color","size","mode"])])),_:1})):m("",!0),u(z,{class:c(["u-line-1",["nomore"==e.status&&1==e.isDot?"u-loadmore__content__dot-text":"u-loadmore__content__text"]]),style:g([T.loadTextStyle]),onClick:T.loadMore},{default:s((()=>[h(p(T.showTextV2),1)])),_:1},8,["style","class","onClick"])])),_:1},8,["class"]),e.line?(n(),d(_,{key:1,length:"140rpx",color:e.lineColor,hairline:!1,dashed:e.dashed},null,8,["color","dashed"])):m("",!0)])),_:1},8,["style"])}],["__scopeId","data-v-d1c40bef"]]);export{_};
