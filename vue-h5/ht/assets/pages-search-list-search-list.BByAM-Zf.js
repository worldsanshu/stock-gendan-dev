import{T as t,bm as e,ag as s,$ as a,c2 as l,W as o,X as i,q as n,x as c,y as h,z as r,A as u,C as d,H as m,I as f,J as p,L as y,M as x,K as _,D as g,E as k,F as S}from"./index-MDKEdHpS.js";import{_ as C}from"./u-icon.BV8HSO_0.js";import{r as L}from"./uni-app.es.4qAlN9XI.js";import{_ as $}from"./u-search.BBRfMwm4.js";import{_ as b}from"./uni-icons.DwctlgQK.js";import{_ as H}from"./u-empty.BSZlzoo9.js";import{v as z,w as D,q as T}from"./stock.BD7lVBb6.js";import{_ as j}from"./_plugin-vue_export-helper.BCo6x5W8.js";const w=j({data:()=>({searchValue:"",historyList:[],list:[],searchList:[],loading:!1,isSearch:!1,key:""}),mounted(){this.getStockSearchHistoryData(),this.authStore=t(),this.historyList=this.authStore.historyStockSearch??[],console.log(this.historyList,99)},onShow(){console.log(e("__HISTORYSTOCKSEARCH__"),88)},methods:{navigateToStockDetail:s,cancel(){a()},handleClearHistory(){const t=this;l({title:this.$t("common.text.systemHint"),content:this.$t("search.text.modalTip"),cancelText:this.$t("common.text.cancel"),confirmText:this.$t("common.text.yes"),confirmColor:"#3cc51f",cancelColor:"#5f646e",success:function(e){e.confirm?(console.log("用户点击确定"),t.historyList=[],t.authStore.setHistoryStockSearch(t.historyList)):e.cancel&&console.log("用户点击取消")}})},handleSetHistory(t){this.historyList.filter((e=>t.code===e.code)).length||(this.historyList=this.historyList.concat({code:t.code,code_title:t.code_title||t.name}),this.authStore.setHistoryStockSearch(this.historyList))},handlJumpDetail(t){this.handleSetHistory(t),s({name:t.code_title||t.name,code:t.code})},getStockSearchHistoryData(){o({title:this.$t("common.text.requesting")}),z().then((t=>{console.log(t),this.list=t.data??[]})).finally((()=>{i()}))},handleChangekey(t){if(this.key=t.replace(/\s/g,""),this.loading)return!1;this.loading=!0,o({title:this.$t("common.text.requesting")}),D({lang:"zh-cn",key:this.key}).then((t=>{this.searchList=t.data??[],this.loading=!1,this.isSearch=!0})).finally((()=>{this.isSearch=!0,this.loading=!1,i()}))},handleAddList(t){const e=this.searchList[t];console.log(e),o({title:this.$t("common.text.requesting")}),T({code:e.code,name:e.name}).then((e=>{e.error?n({title:e.message,icon:"none"}):this.searchList[t].is_self=1})).finally((()=>{i()}))},handleBack(){a()}}},[["render",function(t,e,s,a,l,o){const i=L(c("up-icon"),C),n=d,z=L(c("up-search"),$),D=L(c("uni-icons"),b),T=L(c("up-empty"),H);return h(),r(n,{class:"search-list-page"},{default:u((()=>[m(n,{class:"search-box"},{default:u((()=>[m(n,{class:"bar-box",onClick:o.handleBack},{default:u((()=>[m(i,{name:"arrow-left",size:"32upx",color:"#333"}),m(n,null,{default:u((()=>[f(p(t.$t("common.text.return")),1)])),_:1})])),_:1},8,["onClick"]),m(z,{placeholder:t.$t("search.text.inputPlaceholder"),height:"50upx",style:{"font-size":"12px"},textColor:"#848484",radius:"80upx",inputStyle:"font-size:22upx; font-weight:300",searchIconSize:"28upx",actionText:t.$t("common.text.cancel"),modelValue:l.key,"onUpdate:modelValue":e[0]||(e[0]=t=>l.key=t),onCustom:o.cancel,actionStyle:"color:#333;font-weight:320",onChange:o.handleChangekey},null,8,["placeholder","actionText","modelValue","onCustom","onChange"])])),_:1}),y(m(n,{class:"history-list-box"},{default:u((()=>[m(n,{class:"list-header"},{default:u((()=>[_("span",null,p(t.$t("search.text.historicalSearch")),1),m(n,{onClick:o.handleClearHistory},{default:u((()=>[m(D,{type:"trash",size:"32upx",color:"#999"}),f(" "+p(t.$t("search.text.clearHistory")),1)])),_:1},8,["onClick"])])),_:1}),m(n,{class:"list-box"},{default:u((()=>[(h(!0),g(S,null,k(l.historyList,((t,e)=>(h(),r(n,{key:e,onClick:()=>{o.handlJumpDetail(t)}},{default:u((()=>[m(n,null,{default:u((()=>[f(p(t.code_title),1)])),_:2},1024),_("span",null,p(t.code),1)])),_:2},1032,["onClick"])))),128))])),_:1})])),_:1},512),[[x,l.historyList.length]]),y(m(n,{class:"hot-list-box"},{default:u((()=>[m(n,{class:"list-header-box"},{default:u((()=>[_("span",null,p(t.$t("search.text.hotStocks")),1)])),_:1}),m(n,{class:"list-box"},{default:u((()=>[(h(!0),g(S,null,k(l.list,((t,e)=>(h(),r(n,{class:"list",key:e,onClick:()=>{o.handlJumpDetail(t)}},{default:u((()=>[m(n,{class:"icon-box"},{default:u((()=>[f(p(e+1),1)])),_:2},1024),m(n,null,{default:u((()=>[m(n,null,{default:u((()=>[f(p(t.code_title),1)])),_:2},1024),_("span",null,p(t.code),1)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1})])),_:1},512),[[x,l.list.length&&!l.isSearch]]),y(m(n,{class:"searchlist-box"},{default:u((()=>[(h(!0),g(S,null,k(l.searchList,((t,e)=>(h(),r(n,{class:"list-box",key:e},{default:u((()=>[m(n,{onClick:()=>{o.handlJumpDetail(t)}},{default:u((()=>[m(n,null,{default:u((()=>[f(p(t.name),1)])),_:2},1024),_("span",null,p(t.code),1)])),_:2},1032,["onClick"]),m(n,null,{default:u((()=>[y(m(D,{type:"plus",size:"48upx",color:"red",onClick:()=>{o.handleAddList(e)}},null,8,["onClick"]),[[x,1!==t.is_self]]),y(m(D,{type:"checkbox",size:"48upx",color:"#999"},null,512),[[x,1===t.is_self]])])),_:2},1024)])),_:2},1024)))),128))])),_:1},512),[[x,l.searchList.length&&l.isSearch]]),y(m(n,null,{default:u((()=>[m(T,{mode:"data",width:"160","margin-top":"250rpx",icon:"/static/Vertretung/noMoreData@2x.png"})])),_:1},512),[[x,!l.searchList.length&&l.isSearch]])])),_:1})}],["__scopeId","data-v-8dcfdf6e"]]);export{w as default};
