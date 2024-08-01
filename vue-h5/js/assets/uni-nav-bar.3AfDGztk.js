import{_ as t}from"./uni-icons.BvGH-puM.js";import{b0 as e,x as a,y as l,z as r,A as i,H as n,B as o,aa as s,G as u,a9 as h,I as c,J as d,C as f,O as g}from"./index-B_R0yMjY.js";import{r as b}from"./uni-app.es.BJ_eOOZE.js";import{U as y}from"./uni-status-bar.36jC1oWS.js";import{_}from"./_plugin-vue_export-helper.BCo6x5W8.js";const p=t=>"number"==typeof t?t+"px":t;const k=_({name:"UniNavBar",components:{statusBar:y},emits:["clickLeft","clickRight","clickTitle"],props:{dark:{type:Boolean,default:!1},title:{type:String,default:""},leftText:{type:String,default:""},rightText:{type:String,default:""},leftIcon:{type:String,default:""},rightIcon:{type:String,default:""},fixed:{type:[Boolean,String],default:!1},color:{type:String,default:""},backgroundColor:{type:String,default:""},statusBar:{type:[Boolean,String],default:!1},shadow:{type:[Boolean,String],default:!1},border:{type:[Boolean,String],default:!0},height:{type:[Number,String],default:44},leftWidth:{type:[Number,String],default:60},rightWidth:{type:[Number,String],default:60},stat:{type:[Boolean,String],default:""}},computed:{themeBgColor(){return this.dark?this.backgroundColor?this.backgroundColor:this.dark?"#333":"#FFF":this.backgroundColor||"#FFF"},themeColor(){return this.dark?this.color?this.color:this.dark?"#fff":"#333":this.color||"#333"},navbarHeight(){return p(this.height)},leftIconWidth(){return p(this.leftWidth)},rightIconWidth(){return p(this.rightWidth)}},mounted(){uni.report&&this.stat&&""!==this.title&&uni.report("title",this.title)},methods:{onClickLeft(){this.$emit("clickLeft")},onClickRight(){this.$emit("clickRight")},onClickTitle(){this.$emit("clickTitle")}}},[["render",function(y,_,p,k,m,v){const C=e("status-bar"),x=b(a("uni-icons"),t),S=f,B=g;return l(),r(S,{class:o(["uni-navbar",{"uni-dark":p.dark,"uni-nvue-fixed":p.fixed}])},{default:i((()=>[n(S,{class:o(["uni-navbar__content",{"uni-navbar--fixed":p.fixed,"uni-navbar--shadow":p.shadow,"uni-navbar--border":p.border}]),style:s({"background-color":v.themeBgColor,"border-bottom-color":v.themeColor})},{default:i((()=>[p.statusBar?(l(),r(C,{key:0})):u("",!0),n(S,{style:s({color:v.themeColor,backgroundColor:v.themeBgColor,height:v.navbarHeight}),class:"uni-navbar__header"},{default:i((()=>[n(S,{onClick:v.onClickLeft,class:"uni-navbar__header-btns uni-navbar__header-btns-left",style:s({width:v.leftIconWidth})},{default:i((()=>[h(y.$slots,"left",{},(()=>[p.leftIcon.length>0?(l(),r(S,{key:0,class:"uni-navbar__content_view"},{default:i((()=>[n(x,{color:v.themeColor,type:p.leftIcon,size:"20"},null,8,["color","type"])])),_:1})):u("",!0),p.leftText.length?(l(),r(S,{key:1,class:o([{"uni-navbar-btn-icon-left":!p.leftIcon.length>0},"uni-navbar-btn-text"])},{default:i((()=>[n(B,{style:s({color:v.themeColor,fontSize:"12px"})},{default:i((()=>[c(d(p.leftText),1)])),_:1},8,["style"])])),_:1},8,["class"])):u("",!0)]),!0)])),_:3},8,["onClick","style"]),n(S,{class:"uni-navbar__header-container",onClick:v.onClickTitle},{default:i((()=>[h(y.$slots,"default",{},(()=>[p.title.length>0?(l(),r(S,{key:0,class:"uni-navbar__header-container-inner"},{default:i((()=>[n(B,{class:"uni-nav-bar-text uni-ellipsis-1",style:s({color:v.themeColor})},{default:i((()=>[c(d(p.title),1)])),_:1},8,["style"])])),_:1})):u("",!0)]),!0)])),_:3},8,["onClick"]),n(S,{onClick:v.onClickRight,class:"uni-navbar__header-btns uni-navbar__header-btns-right",style:s({width:v.rightIconWidth})},{default:i((()=>[h(y.$slots,"right",{},(()=>[p.rightIcon.length?(l(),r(S,{key:0},{default:i((()=>[n(x,{color:v.themeColor,type:p.rightIcon,size:"22"},null,8,["color","type"])])),_:1})):u("",!0),p.rightText.length&&!p.rightIcon.length?(l(),r(S,{key:1,class:"uni-navbar-btn-text"},{default:i((()=>[n(B,{class:"uni-nav-bar-right-text",style:s({color:v.themeColor})},{default:i((()=>[c(d(p.rightText),1)])),_:1},8,["style"])])),_:1})):u("",!0)]),!0)])),_:3},8,["onClick","style"])])),_:3},8,["style"])])),_:3},8,["class","style"]),p.fixed?(l(),r(S,{key:0,class:"uni-navbar__placeholder"},{default:i((()=>[p.statusBar?(l(),r(C,{key:0})):u("",!0),n(S,{class:"uni-navbar__placeholder-view",style:s({height:v.navbarHeight})},null,8,["style"])])),_:1})):u("",!0)])),_:3},8,["class"])}],["__scopeId","data-v-f91da7da"]]);export{k as _};
