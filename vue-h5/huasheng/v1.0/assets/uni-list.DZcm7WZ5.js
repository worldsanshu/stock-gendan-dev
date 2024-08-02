import{r as t,t as e,bq as i,br as s,x as l,y as a,z as o,A as n,B as r,G as d,H as c,aa as u,a9 as p,I as h,J as g,C as f,N as m,O as y,bs as b}from"./index-DHIuICwK.js";import{_}from"./uni-icons.COKLvYFy.js";import{r as k}from"./uni-app.es.d3PbdijJ.js";import{_ as w}from"./uni-badge.DYLD1Pat.js";import{_ as S}from"./_plugin-vue_export-helper.BCo6x5W8.js";const x=S({name:"UniListItem",emits:["click","switchChange"],props:{direction:{type:String,default:"row"},title:{type:String,default:""},note:{type:String,default:""},ellipsis:{type:[Number,String],default:0},disabled:{type:[Boolean,String],default:!1},clickable:{type:Boolean,default:!1},showArrow:{type:[Boolean,String],default:!1},link:{type:[Boolean,String],default:!1},to:{type:String,default:""},showBadge:{type:[Boolean,String],default:!1},showSwitch:{type:[Boolean,String],default:!1},switchChecked:{type:[Boolean,String],default:!1},badgeText:{type:String,default:""},badgeType:{type:String,default:"success"},badgeStyle:{type:Object,default:()=>({})},rightText:{type:String,default:""},thumb:{type:String,default:""},thumbSize:{type:String,default:"base"},showExtraIcon:{type:[Boolean,String],default:!1},extraIcon:{type:Object,default:()=>({type:"",color:"#000000",size:20,customPrefix:""})},border:{type:Boolean,default:!0},customStyle:{type:Object,default:()=>({padding:"",backgroundColor:"#FFFFFF"})},keepScrollPosition:{type:Boolean,default:!1}},watch:{"customStyle.padding":{handler(t){"number"==typeof t&&(t+="");let e=t.split(" ");if(1===e.length){const t=e[0];this.padding={top:t,right:t,bottom:t,left:t}}else if(2===e.length){const[t,i]=e;this.padding={top:t,right:i,bottom:t,left:i}}else if(4===e.length){const[t,i,s,l]=e;this.padding={top:t,right:i,bottom:s,left:l}}},immediate:!0}},data:()=>({isFirstChild:!1,padding:{top:"",right:"",bottom:"",left:""}}),mounted(){this.list=this.getForm(),this.list&&(this.list.firstChildAppend||(this.list.firstChildAppend=!0,this.isFirstChild=!0))},methods:{getForm(t="uniList"){let e=this.$parent,i=e.$options.name;for(;i!==t;){if(e=e.$parent,!e)return!1;i=e.$options.name}return e},onClick(){""===this.to?(this.clickable||this.link)&&this.$emit("click",{data:{}}):this.openPage()},onSwitchChange(t){this.$emit("switchChange",t.detail)},openPage(){-1!==["navigateTo","redirectTo","reLaunch","switchTab"].indexOf(this.link)?this.pageApi(this.link):this.pageApi("navigateTo")},pageApi(l){let a={url:this.to,success:t=>{this.$emit("click",{data:t})},fail:t=>{this.$emit("click",{data:t})}};switch(l){case"navigateTo":default:t(a);break;case"redirectTo":s(a);break;case"reLaunch":i(a);break;case"switchTab":e(a)}}}},[["render",function(t,e,i,s,S,x){const B=f,C=m,T=k(l("uni-icons"),_),$=y,F=k(l("uni-badge"),w),I=b;return a(),o(B,{class:r([{"uni-list-item--disabled":i.disabled},"uni-list-item"]),style:u({"background-color":i.customStyle.backgroundColor}),"hover-class":!i.clickable&&!i.link||i.disabled||i.showSwitch?"":"uni-list-item--hover",onClick:x.onClick},{default:n((()=>[S.isFirstChild?d("",!0):(a(),o(B,{key:0,class:r(["border--left",{"uni-list--border":i.border}])},null,8,["class"])),c(B,{class:r(["uni-list-item__container",{"container--right":i.showArrow||i.link,"flex--direction":"column"===i.direction}]),style:u({paddingTop:S.padding.top,paddingLeft:S.padding.left,paddingRight:S.padding.right,paddingBottom:S.padding.bottom})},{default:n((()=>[p(t.$slots,"header",{},(()=>[c(B,{class:"uni-list-item__header"},{default:n((()=>[i.thumb?(a(),o(B,{key:0,class:"uni-list-item__icon"},{default:n((()=>[c(C,{src:i.thumb,class:r(["uni-list-item__icon-img",["uni-list--"+i.thumbSize]])},null,8,["src","class"])])),_:1})):i.showExtraIcon?(a(),o(B,{key:1,class:"uni-list-item__icon"},{default:n((()=>[c(T,{customPrefix:i.extraIcon.customPrefix,color:i.extraIcon.color,size:i.extraIcon.size,type:i.extraIcon.type},null,8,["customPrefix","color","size","type"])])),_:1})):d("",!0)])),_:1})]),!0),p(t.$slots,"body",{},(()=>[c(B,{class:r(["uni-list-item__content",{"uni-list-item__content--center":i.thumb||i.showExtraIcon||i.showBadge||i.showSwitch}])},{default:n((()=>[i.title?(a(),o($,{key:0,class:r(["uni-list-item__content-title",[0!==i.ellipsis&&i.ellipsis<=2?"uni-ellipsis-"+i.ellipsis:""]])},{default:n((()=>[h(g(i.title),1)])),_:1},8,["class"])):d("",!0),i.note?(a(),o($,{key:1,class:"uni-list-item__content-note"},{default:n((()=>[h(g(i.note),1)])),_:1})):d("",!0)])),_:1},8,["class"])]),!0),p(t.$slots,"footer",{},(()=>[i.rightText||i.showBadge||i.showSwitch?(a(),o(B,{key:0,class:r(["uni-list-item__extra",{"flex--justify":"column"===i.direction}])},{default:n((()=>[i.rightText?(a(),o($,{key:0,class:"uni-list-item__extra-text"},{default:n((()=>[h(g(i.rightText),1)])),_:1})):d("",!0),i.showBadge?(a(),o(F,{key:1,type:i.badgeType,text:i.badgeText,"custom-style":i.badgeStyle},null,8,["type","text","custom-style"])):d("",!0),i.showSwitch?(a(),o(I,{key:2,disabled:i.disabled,checked:i.switchChecked,onChange:x.onSwitchChange},null,8,["disabled","checked","onChange"])):d("",!0)])),_:1},8,["class"])):d("",!0)]),!0)])),_:3},8,["class","style"]),i.showArrow||i.link?(a(),o(T,{key:1,size:16,class:"uni-icon-wrapper",color:"#bbb",type:"arrowright"})):d("",!0)])),_:3},8,["class","style","hover-class","onClick"])}],["__scopeId","data-v-77963a5a"]]);const B=S({name:"uniList","mp-weixin":{options:{multipleSlots:!1}},props:{stackFromEnd:{type:Boolean,default:!1},enableBackToTop:{type:[Boolean,String],default:!1},scrollY:{type:[Boolean,String],default:!1},border:{type:Boolean,default:!0},renderReverse:{type:Boolean,default:!1}},created(){this.firstChildAppend=!1},methods:{loadMore(t){this.$emit("scrolltolower")},scroll(t){this.$emit("scroll",t)}}},[["render",function(t,e,i,s,l,r){const c=f;return a(),o(c,{class:"uni-list uni-border-top-bottom"},{default:n((()=>[i.border?(a(),o(c,{key:0,class:"uni-list--border-top"})):d("",!0),p(t.$slots,"default",{},void 0,!0),i.border?(a(),o(c,{key:1,class:"uni-list--border-bottom"})):d("",!0)])),_:3})}],["__scopeId","data-v-c1d7c358"]]);export{x as _,B as a};
