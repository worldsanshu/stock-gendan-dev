import{Z as t,$ as e,W as s,X as a,q as i,x as l,y as r,z as n,A as o,r as c,t as u,C as d,H as p,D as f,E as m,F as h,I as g,J as k}from"./index-v5N4FCa5.js";import{_}from"./u-icon.C6i9Eu8q.js";import{r as v}from"./uni-app.es.BBb7LdIV.js";import{_ as x}from"./u-navbar.T0-00ECq.js";import{_ as y}from"./u-sticky.DiF5YgOY.js";import{_ as b}from"./u-image.BZj6njDc.js";import{H as T,I as j}from"./index.Dm55e1Sd.js";import{_ as F}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-status-bar.BFA7c7rh.js";import"./u-transition.BzAsRY_B.js";const $=F({data(){return{lists:[],params:{OA:"APP",lang:"zh-cn"},statusType:{0:this.$t("noviceTask.text.go"),1:this.$t("noviceTask.text.receive"),2:this.$t("noviceTask.text.received")}}},mounted(){this.getData()},methods:{back:t,handleJumpBack(){e()},getData(){s({title:this.$t("common.text.requesting")}),T(this.params).then((t=>{var e;console.log(t),this.lists=(null==(e=t.data)?void 0:e.task.filter(((t,e)=>4!==e&&5!==e)))??[]})).finally((()=>{a()}))},handleTaskGift(t){s({title:this.$t("common.text.requesting")}),j({id:t}).then((t=>{t.error?i({title:t.message,icon:"none"}):(i({title:this.$t("noviceTask.text.successfullyReceived"),icon:"none"}),this.lists=[],this.getData())})).finally((()=>{a()}))},handleBar(t){const e={0:()=>{},1:()=>{c({url:"/pages/user-center-profile-real-name-authentication/user-center-profile-real-name-authentication"})},2:()=>{c({url:"/pages/user-center-recharge/user-center-recharge?origin=1"})},3:()=>{c({url:"/pages/record/record?origin=1"})},4:()=>{u({url:"/pages/trade/trade"})},5:()=>{u({url:"/pages/trade/trade"})}};this.lists[t].status||e[t](),1===this.lists[t].status&&this.handleTaskGift(this.lists[t].id)}}},[["render",function(t,e,s,a,i,c){const u=v(l("up-icon"),_),T=d,j=v(l("up-navbar"),x),F=v(l("up-sticky"),y),$=v(l("up-image"),b);return r(),n(T,{class:"newbie-task-page"},{default:o((()=>[p(F,{"bg-color":"#FF4B2B",style:{width:"100%",top:"0"}},{default:o((()=>[p(T,{style:{top:"0",height:"120upx",display:"flex","align-items":"center"}},{default:o((()=>[p(j,{title:t.$t("page.title.noviceTask"),style:{width:"100%"},safeAreaInsetTop:!1,fixed:!1,"bg-color":"#FF4B2B",titleStyle:{color:"#FFF",fontSize:"32upx",outerHeight:"90upx"}},{left:o((()=>[p(T,{class:"u-nav-slot"},{default:o((()=>[p(u,{name:"arrow-left",size:"19",color:"#fff",onClick:c.back},null,8,["onClick"])])),_:1})])),_:1},8,["title"])])),_:1})])),_:1}),p($,{style:{background:"#ee8792"},width:"100%",src:"/static/images/novice_task.png"}),p(T,{class:"newbie-task-content"},{default:o((()=>[(r(!0),f(h,null,m(i.lists,((t,e)=>(r(),n(T,{class:"task-list",key:e},{default:o((()=>[p(T,null,{default:o((()=>[p(T,{class:"title"},{default:o((()=>[g(k(t.name),1)])),_:2},1024),p(T,{class:"infos"},{default:o((()=>[g(k(t.note),1)])),_:2},1024),p(T,null,{default:o((()=>[g(k(t.money),1)])),_:2},1024)])),_:2},1024),p(T,{class:"btn",onClick:()=>{c.handleBar(e)}},{default:o((()=>[g(k(i.statusType[t.status]),1)])),_:2},1032,["onClick"])])),_:2},1024)))),128))])),_:1})])),_:1})}],["__scopeId","data-v-f7c74cdc"]]);export{$ as default};
