import{a1 as t,a2 as e,a3 as a,a5 as l,al as r,aV as o,aU as n,$ as s,x as i,y as c,z as f,A as u,aa as d,G as _,H as b,B as h,a9 as g,I as y,J as p,C as v,O as m}from"./index-DhC4eQBs.js";import{_ as x}from"./u-status-bar.D_4vD7Fy.js";import{r as C}from"./uni-app.es.Cgdy7FaG.js";import{_ as k}from"./u-icon.Da4FNGHq.js";import{_ as I}from"./_plugin-vue_export-helper.BCo6x5W8.js";const S=I({name:"u-navbar",mixins:[e,a,{props:{safeAreaInsetTop:{type:Boolean,default:()=>t.navbar.safeAreaInsetTop},placeholder:{type:Boolean,default:()=>t.navbar.placeholder},fixed:{type:Boolean,default:()=>t.navbar.fixed},border:{type:Boolean,default:()=>t.navbar.border},leftIcon:{type:String,default:()=>t.navbar.leftIcon},leftText:{type:String,default:()=>t.navbar.leftText},rightText:{type:String,default:()=>t.navbar.rightText},rightIcon:{type:String,default:()=>t.navbar.rightIcon},title:{type:[String,Number],default:()=>t.navbar.title},bgColor:{type:String,default:()=>t.navbar.bgColor},titleWidth:{type:[String,Number],default:()=>t.navbar.titleWidth},height:{type:[String,Number],default:()=>t.navbar.height},leftIconSize:{type:[String,Number],default:()=>t.navbar.leftIconSize},leftIconColor:{type:String,default:()=>t.navbar.leftIconColor},autoBack:{type:Boolean,default:()=>t.navbar.autoBack},titleStyle:{type:[String,Object],default:()=>t.navbar.titleStyle}}}],data:()=>({}),emits:["leftClick","rightClick"],methods:{addStyle:l,addUnit:r,sys:o,getPx:n,leftClick(){this.$emit("leftClick"),this.autoBack&&s()},rightClick(){this.$emit("rightClick")}}},[["render",function(t,e,a,l,r,o){const n=v,s=C(i("u-status-bar"),x),I=C(i("u-icon"),k),S=m;return c(),f(n,{class:"u-navbar"},{default:u((()=>[t.fixed&&t.placeholder?(c(),f(n,{key:0,class:"u-navbar__placeholder",style:d({height:o.addUnit(o.getPx(t.height)+o.sys().statusBarHeight,"px")})},null,8,["style"])):_("",!0),b(n,{class:h([t.fixed&&"u-navbar--fixed"])},{default:u((()=>[t.safeAreaInsetTop?(c(),f(s,{key:0,bgColor:t.bgColor},null,8,["bgColor"])):_("",!0),b(n,{class:h(["u-navbar__content",[t.border&&"u-border-bottom"]]),style:d({height:o.addUnit(t.height),backgroundColor:t.bgColor})},{default:u((()=>[b(n,{class:"u-navbar__content__left","hover-class":"u-navbar__content__left--hover","hover-start-time":"150",onClick:o.leftClick},{default:u((()=>[g(t.$slots,"left",{},(()=>[t.leftIcon?(c(),f(I,{key:0,name:t.leftIcon,size:t.leftIconSize,color:t.leftIconColor},null,8,["name","size","color"])):_("",!0),t.leftText?(c(),f(S,{key:1,style:d({color:t.leftIconColor}),class:"u-navbar__content__left__text"},{default:u((()=>[y(p(t.leftText),1)])),_:1},8,["style"])):_("",!0)]),!0)])),_:3},8,["onClick"]),g(t.$slots,"center",{},(()=>[b(S,{class:"u-line-1 u-navbar__content__title",style:d([{width:o.addUnit(t.titleWidth)},o.addStyle(t.titleStyle)])},{default:u((()=>[y(p(t.title),1)])),_:1},8,["style"])]),!0),t.$slots.right||t.rightIcon||t.rightText?(c(),f(n,{key:0,class:"u-navbar__content__right",onClick:o.rightClick},{default:u((()=>[g(t.$slots,"right",{},(()=>[t.rightIcon?(c(),f(I,{key:0,name:t.rightIcon,size:"20"},null,8,["name"])):_("",!0),t.rightText?(c(),f(S,{key:1,class:"u-navbar__content__right__text"},{default:u((()=>[y(p(t.rightText),1)])),_:1})):_("",!0)]),!0)])),_:3},8,["onClick"])):_("",!0)])),_:3},8,["class","style"])])),_:3},8,["class"])])),_:3})}],["__scopeId","data-v-728675b3"]]);export{S as _};
