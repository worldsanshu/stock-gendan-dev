import{m as t,u as e,n as a,a as i,b as l,c as n,d as s,e as o,f as r,s as d,g as c,h as u,i as m,j as f,k as h,l as p,o as _,p as g,q as w,r as x,t as y,v as k,w as C,x as v,y as b,z as T,A as S,B as I,C as L,D as $,E as j,F as A,G as D,H as N,I as P,J as F,K as z,L as U,M as E,N as B,S as R,O as J,P as M,Q as O}from"./index-DugkB_-4.js";import{_ as H}from"./u-icon.Ca1c56qc.js";import{r as G}from"./uni-app.es.CX-CC-lz.js";import{_ as K}from"./u-image.1MCDMfbz.js";import{_ as W}from"./u-tabs.DCj_ugoh.js";import{_ as q}from"./u-modal.DgcFYL2i.js";import{i as Q,a as V}from"./index.CCikdCXX.js";import{_ as X}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-transition.bUAwPu-p.js";import"./u-line.BpwwmqXn.js";import"./u-loading-icon.CCS9DSWM.js";import"./u-popup.D4xZoMdU.js";import"./u-status-bar.CdB_NfJN.js";const Y=""+new URL("benefit-icon1-AHkCKix5.png",import.meta.url).href,Z=""+new URL("benefit-icon2-BNpyv1dn.png",import.meta.url).href,tt=""+new URL("ann-wave-B76Uc-Cf.png",import.meta.url).href;const et=X({data(){return{allList:"",switc:"",modalContent:"",modalShow:!1,cmslist:[],kuaixun:[],newslist:[],bannerList1:[],bannerList2:[],bannerList3:[],tabs:[{name:this.$t("home.text.hotNews")}],tabCurrent:0,downloadAppShow:!0,platform:"",daily:"",homeOperation:[]}},computed:{...t(e,["appConfig"])},onLoad(t){this.platform=t.platform,this.refreshIndexPopupContent(),this.refreshIndexData(),Q({}).then((t=>{this.allList=t.data})),V().then((t=>{this.switc=t.data.module,this.daily=t.data}))},onShow(){this.getPageEntryData()},methods:{navigateToIndexTradingRules:a,navigateToIndexNews:i,navigateToSearch:l,navigateToIndexActivityCenter:n,navigateToAgentCenter:s,navigateToIndexCheckInCenter:o,navigateToUserCenterRecharge:r,switchToMarket:d,navigateToIndexPartner:c,navigateToCopyTrading:u,navigateToUserCenterStationMessageNotice:m,navigateToArticleDetail:f,switchToStarInvestor:h,navigateToCustomerService:p,async getPageEntryData(){const t=await _();0===t.error&&(this.homeOperation=t.data.home)},async refreshIndexPopupContent(){const{error:t,data:e,message:a}=await g({lang:"zh-cn"});t?await w({title:a||this.$t("home.text.interfaceError"),icon:"none"}):(this.modalContent=e.content,this.modalShow=!!e.content)},skip(t){x({url:t})},operationJunk(t){1==t.url_type?1==t.url_level?y({url:t.url}):2==t.url_level&&x({url:t.url}):2==t.url_type&&setTimeout((()=>window.open(t.url,"_blank")))},async refreshIndexData(){const{error:t,data:e,message:a}=await k();if(t)return void(await w({title:a||this.$t("home.text.interfaceError"),icon:"none"}));const{cmslist:i,ADlistNew:l,kuaixun:n,newslist:s}=e;this.cmslist=i,this.kuaixun=n,this.newslist=s,this.bannerList1=l.ad1,this.bannerList2=l.ad2,this.bannerList3=l.ad3},handleJumpPzIndex(t){x({url:`/pages/pz-module/index?index=${t}`})},handleDownloadApp(){window.open(this.appConfig.appdown,"_blank")},onSkipPageFn(t){1===t.url_type?1===t.url_level?y({url:t.url}):2===t.url_level&&x({url:t.url}):2===t.url_type&&window.open(t.url,"_blank")}},watch:{appConfig:{handler(t){t.web_site_title&&C({title:t.web_site_title}),this.platform&&"app"===this.platform.toLowerCase()&&(this.downloadAppShow=!1)},immediate:!0}}},[["render",function(t,e,a,i,l,n){const s=B,o=O,r=R,d=I,c=G(v("up-icon"),H),u=J,m=G(v("up-image"),K),f=G(v("up-tabs"),W),h=M,p=G(v("up-modal"),q);return L(),b(d,{class:S(["index-page",{"index-page-padding":0===l.bannerList1.length}])},{default:T((()=>[l.bannerList1.length?(L(),b(r,{key:0,class:"muis-image",circular:"","indicator-dots":!1,autoplay:"",interval:3e3,duration:500},{default:T((()=>[(L(!0),$(A,null,j(l.bannerList1,((t,e)=>(L(),b(o,{key:e,onClick:e=>n.onSkipPageFn(t)},{default:T((()=>[N(s,{src:t.img_url,style:{width:"100%"}},null,8,["src"])])),_:2},1032,["onClick"])))),128))])),_:1})):D("",!0),N(d,{class:"header-search",onClick:n.navigateToSearch},{default:T((()=>[N(d,{class:"header-search-siz"}),N(d,{class:"text"},{default:T((()=>[N(c,{name:"search",size:"32rpx",color:"#fff",style:{"margin-right":"4rpx"}}),P(" "+F(t.$t("home.text.searchInput")),1)])),_:1})])),_:1},8,["onClick"]),N(d,{class:"bars-box"},{default:T((()=>[(L(!0),$(A,null,j(l.homeOperation,(t=>(L(),b(d,{key:t.name,onClick:e=>n.operationJunk(t)},{default:T((()=>[N(s,{style:{width:"42upx",height:"46upx"},src:t.img_url},null,8,["src"]),N(u,null,{default:T((()=>[P(F(t.name),1)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1}),l.bannerList3.length?(L(),b(r,{key:1,class:"muis-images",circular:"","indicator-dots":"true","indicator-color":"rgb(246,246,246)","indicator-active-color":"#fff",autoplay:"",interval:2e3,duration:500},{default:T((()=>[(L(!0),$(A,null,j(l.bannerList3,((t,e)=>(L(),b(o,{key:e,onClick:e=>n.onSkipPageFn(t)},{default:T((()=>[N(s,{src:t.img_url,mode:""},null,8,["src"])])),_:2},1032,["onClick"])))),128))])),_:1})):D("",!0),N(d,{class:"notice-box"},{default:T((()=>[z("strong",null,F(t.$t("home.text.announcement")),1),N(r,{class:"notice-swiper",circular:"",autoplay:"",vertical:""},{default:T((()=>[(L(!0),$(A,null,j(l.cmslist,(t=>(L(),b(o,{key:t.id},{default:T((()=>[N(d,{class:"swiper-item uni-bg-red",style:{},onClick:e=>n.navigateToArticleDetail({id:t.id})},{default:T((()=>[P(F(t.title),1)])),_:2},1032,["onClick"])])),_:2},1024)))),128))])),_:1}),N(u,{onClick:n.navigateToUserCenterStationMessageNotice},{default:T((()=>[P(F(t.$t("home.text.more")),1)])),_:1},8,["onClick"])])),_:1}),l.bannerList2.length?(L(),b(r,{key:2,class:"muis-images",circular:"","indicator-dots":"true","indicator-color":"rgb(246,246,246)","indicator-active-color":"#fff",autoplay:"",interval:2e3,duration:500},{default:T((()=>[(L(!0),$(A,null,j(l.bannerList2,((t,e)=>(L(),b(o,{key:e,onClick:e=>n.onSkipPageFn(t)},{default:T((()=>[N(s,{src:t.img_url,mode:""},null,8,["src"])])),_:2},1032,["onClick"])))),128))])),_:1})):D("",!0),l.daily?(L(),$(A,{key:3},["1"===l.daily.daily_welfare.activity.daily_attendance||"1"===l.daily.daily_welfare.activity.friend_invitation||"1"===l.daily.daily_welfare.activity.novice_gift_pack?(L(),b(d,{key:0,class:"welfare-box"},{default:T((()=>[N(d,{class:"welfare-header"},{default:T((()=>[P(F(l.daily.daily_welfare.daily_welfare_title),1)])),_:1}),N(d,{class:"welfare-content"},{default:T((()=>["1"===l.daily.daily_welfare.activity.daily_attendance||"1"===l.daily.daily_welfare.activity.friend_invitation||"1"===l.daily.daily_welfare.activity.novice_gift_pack?(L(),b(d,{key:0,class:"list",onClick:n.navigateToIndexActivityCenter},{default:T((()=>[N(d,null,{default:T((()=>[N(d,{class:"title"},{default:T((()=>[P(F(t.$t("home.text.activityCenter")),1)])),_:1}),N(d,null,{default:T((()=>[P(F(t.$t("home.text.participate")),1)])),_:1}),N(d,{class:"btn"},{default:T((()=>[P(F(t.$t("home.text.getInvolvedNow")),1)])),_:1})])),_:1}),N(s,{mode:"widthFix",style:{width:"170upx",bottom:"10rpx","z-index":"0",right:"8rpx"},src:Y})])),_:1},8,["onClick"])):D("",!0),"1"===l.daily.daily_welfare.daily_attendance?(L(),b(d,{key:1,class:"list",onClick:n.navigateToIndexCheckInCenter},{default:T((()=>[N(d,null,{default:T((()=>[N(d,{class:"title"},{default:T((()=>[P(F(t.$t("home.text.checkInCenter")),1)])),_:1}),N(d,null,{default:T((()=>[P(F(t.$t("home.text.continuous")),1)])),_:1}),N(d,{class:"btn"},{default:T((()=>[P(F(t.$t("home.text.checkInNow")),1)])),_:1})])),_:1}),N(s,{mode:"widthFix",style:{width:"100upx"},src:Z})])),_:1},8,["onClick"])):D("",!0)])),_:1})])),_:1})):D("",!0)],64)):D("",!0),N(d,{class:"news-box"},{default:T((()=>[N(d,{class:"news-header"},{default:T((()=>[N(d,null,{default:T((()=>[N(m,{src:"/static/images/jie.png",width:"105rpx",height:"44rpx"}),N(u,{class:"news-head-tse"},{default:T((()=>[P(F(t.$t("home.text.liveAnalysis")),1)])),_:1})])),_:1}),N(u,{onClick:e[0]||(e[0]=t=>n.navigateToIndexNews({origin:1}))},{default:T((()=>[P(F(t.$t("home.text.more")),1)])),_:1})])),_:1}),N(d,{class:"market-list-box"},{default:T((()=>[(L(!0),$(A,null,j(l.kuaixun,(t=>(L(),b(d,{class:"list-box",key:t.id},{default:T((()=>[N(d,{class:"icon-box"}),N(d,null,{default:T((()=>[N(d,{class:"info"},{default:T((()=>[P(F(t.rich_text),1)])),_:2},1024),N(d,{class:"time"},{default:T((()=>[P(F(t.update_time),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128))])),_:1})])),_:1}),N(d,{class:"hot-news-box"},{default:T((()=>[N(d,{class:"hot-news-header"},{default:T((()=>[N(f,{list:l.tabs,current:l.tabCurrent,lineColor:"red",onChange:e[1]||(e[1]=t=>l.tabCurrent=t),lineWidth:"60",lineHeight:"8rpx",activeStyle:{color:"#303133",fontWeight:"bold",transform:"scale(1.05)"},inactiveStyle:{color:"#606266",transform:"scale(1)"}},null,8,["list","current","activeStyle","inactiveStyle"]),N(d,{onClick:e[2]||(e[2]=t=>n.navigateToIndexNews({origin:1===l.tabCurrent?1:0}))},{default:T((()=>[P(F(t.$t("home.text.more")),1)])),_:1})])),_:1}),U(N(d,{class:"news-content-box"},{default:T((()=>[(L(!0),$(A,null,j(l.newslist,(t=>(L(),b(d,{class:"list",key:t.id,onClick:e=>n.navigateToArticleDetail({id:t.id})},{default:T((()=>[N(d,null,{default:T((()=>[N(d,{class:"info"},{default:T((()=>[P(F(t.title),1)])),_:2},1024),N(d,{class:"time"},{default:T((()=>[P(F(t.time),1)])),_:2},1024)])),_:2},1024),N(s,{mode:"aspectFill",style:{width:"158upx","max-height":"112upx"},src:t.cj_thumbs},null,8,["src"])])),_:2},1032,["onClick"])))),128))])),_:1},512),[[E,0===l.tabCurrent]]),U(N(d,{class:"news-content-box"},{default:T((()=>[(L(!0),$(A,null,j(l.kuaixun,(t=>(L(),b(d,{class:"list",key:t.id,onClick:e=>n.navigateToArticleDetail({id:t.id,type:2})},{default:T((()=>[N(d,null,{default:T((()=>[N(d,{class:"info"},{default:T((()=>[P(F(t.short_rich_text),1)])),_:2},1024),N(d,{class:"time"},{default:T((()=>[P(F(t.update_time),1)])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1},512),[[E,1===l.tabCurrent]])])),_:1}),N(p,{show:l.modalShow,width:"650rpx",title:"",class:"waptc-modal-box",onCancel:e[4]||(e[4]=t=>l.modalShow=!1),onConfirm:e[5]||(e[5]=t=>l.modalShow=!1),onClose:e[6]||(e[6]=t=>l.modalShow=!1),showConfirmButton:!1},{default:T((()=>[N(d,{class:"slot-content"},{default:T((()=>[N(d,{class:"img-box"},{default:T((()=>[N(d,null,{default:T((()=>[P(F(t.$t("home.text.noticeTitle")),1)])),_:1}),N(s,{mode:"widthFix",style:{width:"100%",position:"absolute",bottom:"0",left:"0"},src:tt})])),_:1}),N(d,{style:{padding:"24upx"}},{default:T((()=>[N(h,{nodes:l.modalContent,class:"mun-is"},null,8,["nodes"])])),_:1}),N(d,{class:"modal-footer"},{default:T((()=>[N(d,{class:"btn",onClick:e[3]||(e[3]=t=>l.modalShow=!1)},{default:T((()=>[P(F(t.$t("home.text.confirmText")),1)])),_:1})])),_:1})])),_:1})])),_:1},8,["show"])])),_:1},8,["class"])}],["__scopeId","data-v-69655aef"]]);export{et as default};