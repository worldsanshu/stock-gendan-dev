import{T as t,bm as e,ag as s,$ as a,c2 as o,W as l,X as i,q as n,x as c,y as h,z as r,A as d,C as u,H as m,I as p,J as f,L as y,M as x,K as _,D as g,E as k,F as S}from"./index-DAL43Vnu.js";import{_ as C}from"./u-icon.CQfWR99v.js";import{r as L}from"./uni-app.es.b_6IPGg9.js";import{_ as b}from"./u-search.DVnLlRJI.js";import{_ as $}from"./uni-icons.CT0tYDb7.js";import{_ as H}from"./u-empty.DFUmR_V7.js";import{v as T,w as z,q as D}from"./stock.xUq1nk-v.js";import{_ as j}from"./_plugin-vue_export-helper.BCo6x5W8.js";const w=j({data:()=>({searchValue:"",historyList:[],list:[],searchList:[],loading:!1,isSearch:!1,key:""}),mounted(){this.getStockSearchHistoryData(),this.authStore=t(),this.historyList=this.authStore.historyStockSearch??[],console.log(this.historyList,99)},onShow(){console.log(e("__HISTORYSTOCKSEARCH__"),88)},methods:{navigateToStockDetail:s,cancel(){a()},handleClearHistory(){const t=this;o({title:this.$t("common.text.systemHint"),content:this.$t("search.text.modalTip"),cancelText:this.$t("common.text.cancel"),confirmText:this.$t("common.text.yes"),confirmColor:"#3cc51f",cancelColor:"#5f646e",success:function(e){e.confirm?(console.log("用户点击确定"),t.historyList=[],t.authStore.setHistoryStockSearch(t.historyList)):e.cancel&&console.log("用户点击取消")}})},handleSetHistory(t){this.historyList.filter((e=>t.code===e.code)).length||(this.historyList=this.historyList.concat({code:t.code,code_title:t.code_title||t.name}),this.authStore.setHistoryStockSearch(this.historyList))},handlJumpDetail(t){this.handleSetHistory(t),s({name:t.code_title||t.name,code:t.code})},getStockSearchHistoryData(){l({title:this.$t("common.text.requesting")}),T().then((t=>{console.log(t),this.list=t.data??[]})).finally((()=>{i()}))},handleChangekey(t){if(this.key=t.replace(/\s/g,""),this.loading)return!1;this.loading=!0,l({title:this.$t("common.text.requesting")}),z({lang:"zh-cn",key:this.key}).then((t=>{this.searchList=t.data??[],this.loading=!1,this.isSearch=!0})).finally((()=>{this.isSearch=!0,this.loading=!1,i()}))},handleAddList(t){const e=this.searchList[t];console.log(e),l({title:this.$t("common.text.requesting")}),D({code:e.code,name:e.name}).then((e=>{e.error?n({title:e.message,icon:"none"}):this.searchList[t].added=!0})).finally((()=>{i()}))},handleBack(){a()}}},[["render",function(t,e,s,a,o,l){const i=L(c("up-icon"),C),n=u,T=L(c("up-search"),b),z=L(c("uni-icons"),$),D=L(c("up-empty"),H);return h(),r(n,{class:"search-list-page"},{default:d((()=>[m(n,{class:"search-box"},{default:d((()=>[m(n,{class:"bar-box",onClick:l.handleBack},{default:d((()=>[m(i,{name:"arrow-left",size:"32upx",color:"#333"}),m(n,null,{default:d((()=>[p(f(t.$t("common.text.return")),1)])),_:1})])),_:1},8,["onClick"]),m(T,{placeholder:t.$t("search.text.inputPlaceholder"),height:"50upx",style:{"font-size":"12px"},textColor:"#848484",radius:"80upx",inputStyle:"font-size:22upx; font-weight:300",searchIconSize:"28upx",actionText:t.$t("common.text.cancel"),modelValue:o.key,"onUpdate:modelValue":e[0]||(e[0]=t=>o.key=t),onCustom:l.cancel,actionStyle:"color:#333;font-weight:320",onChange:l.handleChangekey},null,8,["placeholder","actionText","modelValue","onCustom","onChange"])])),_:1}),y(m(n,{class:"history-list-box"},{default:d((()=>[m(n,{class:"list-header"},{default:d((()=>[_("span",null,f(t.$t("search.text.historicalSearch")),1),m(n,{onClick:l.handleClearHistory},{default:d((()=>[m(z,{type:"trash",size:"32upx",color:"#999"}),p(" "+f(t.$t("search.text.clearHistory")),1)])),_:1},8,["onClick"])])),_:1}),m(n,{class:"list-box"},{default:d((()=>[(h(!0),g(S,null,k(o.historyList,((t,e)=>(h(),r(n,{key:e,onClick:()=>{l.handlJumpDetail(t)}},{default:d((()=>[m(n,null,{default:d((()=>[p(f(t.code_title),1)])),_:2},1024),_("span",null,f(t.code),1)])),_:2},1032,["onClick"])))),128))])),_:1})])),_:1},512),[[x,o.historyList.length]]),y(m(n,{class:"hot-list-box"},{default:d((()=>[m(n,{class:"list-header-box"},{default:d((()=>[_("span",null,f(t.$t("search.text.hotStocks")),1)])),_:1}),m(n,{class:"list-box"},{default:d((()=>[(h(!0),g(S,null,k(o.list,((t,e)=>(h(),r(n,{class:"list",key:e,onClick:()=>{l.handlJumpDetail(t)}},{default:d((()=>[m(n,{class:"icon-box"},{default:d((()=>[p(f(e+1),1)])),_:2},1024),m(n,null,{default:d((()=>[m(n,null,{default:d((()=>[p(f(t.code_title),1)])),_:2},1024),_("span",null,f(t.code),1)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1})])),_:1},512),[[x,o.list.length&&!o.isSearch]]),y(m(n,{class:"searchlist-box"},{default:d((()=>[(h(!0),g(S,null,k(o.searchList,((t,e)=>(h(),r(n,{class:"list-box",key:e},{default:d((()=>[m(n,{onClick:()=>{l.handlJumpDetail(t)}},{default:d((()=>[m(n,null,{default:d((()=>[p(f(t.name),1)])),_:2},1024),_("span",null,f(t.code),1)])),_:2},1032,["onClick"]),m(n,null,{default:d((()=>[y(m(z,{type:"plus",size:"48upx",color:"red",onClick:()=>{l.handleAddList(e)}},null,8,["onClick"]),[[x,!t.added]]),y(m(z,{type:"checkbox",size:"48upx",color:"#999"},null,512),[[x,t.added]])])),_:2},1024)])),_:2},1024)))),128))])),_:1},512),[[x,o.searchList.length&&o.isSearch]]),y(m(n,null,{default:d((()=>[m(D,{mode:"data",width:"160","margin-top":"250rpx",icon:"/static/Vertretung/noMoreData@2x.png"})])),_:1},512),[[x,!o.searchList.length&&o.isSearch]])])),_:1})}],["__scopeId","data-v-8a4786da"]]);export{w as default};
