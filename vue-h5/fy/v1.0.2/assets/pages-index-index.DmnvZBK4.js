import{m as t,u as e,n as a,a as i,b as l,c as n,d as s,e as o,f as r,s as d,g as c,h as u,i as f,j as h,k as m,l as p,o as _,p as g,q as w,r as x,t as y,v as k,w as C,x as v,y as b,z as T,A as S,B as I,C as L,D as $,E as j,F as A,G as N,H as P,I as D,J as F,K as z,L as E,M as O,N as U,S as B,O as J,P as M,Q as H}from"./index-BI_M_jYu.js";import{_ as R}from"./u-icon.BII4vsMV.js";import{r as W}from"./uni-app.es.CYmSWya5.js";import{_ as K}from"./u-image.DDbHDv59.js";import{_ as Q}from"./u-tabs.D3HjEllM.js";import{_ as q}from"./u-modal.D94YyUKE.js";import{i as G,a as V}from"./index.B27wsiGI.js";import{_ as X}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-transition.zezQZfuL.js";import"./u-line.DvVv571C.js";import"./u-loading-icon.DsT6vaCF.js";import"./u-popup.C-Gt9tak.js";import"./u-status-bar.P7XHzG_e.js";const Y=X({data(){return{allList:"",switc:"",modalContent:"",modalShow:!1,cmslist:[],kuaixun:[],newslist:[],bannerList1:[],bannerList2:[],bannerList3:[],tabs:[{name:this.$t("home.text.hotNews")}],tabCurrent:0,downloadAppShow:!0,platform:"",daily:"",homeOperation:[]}},computed:{...t(e,["appConfig"])},onLoad(t){this.platform=t.platform,this.refreshIndexPopupContent(),this.refreshIndexData(),G({}).then((t=>{this.allList=t.data})),V().then((t=>{this.switc=t.data.module,this.daily=t.data}))},onShow(){this.getPageEntryData()},methods:{navigateToIndexTradingRules:a,navigateToIndexNews:i,navigateToSearch:l,navigateToIndexActivityCenter:n,navigateToAgentCenter:s,navigateToIndexCheckInCenter:o,navigateToUserCenterRecharge:r,switchToMarket:d,navigateToIndexPartner:c,navigateToCopyTrading:u,navigateToUserCenterStationMessageNotice:f,navigateToArticleDetail:h,switchToStarInvestor:m,navigateToCustomerService:p,async getPageEntryData(){const t=await _();0===t.error&&(this.homeOperation=t.data.home)},async refreshIndexPopupContent(){const{error:t,data:e,message:a}=await g({lang:"zh-cn"});t?await w({title:a||this.$t("home.text.interfaceError"),icon:"none"}):(this.modalContent=e.content,this.modalShow=!!e.content)},skip(t){x({url:t})},operationJunk(t){1==t.url_type?1==t.url_level?y({url:t.url}):2==t.url_level&&x({url:t.url}):2==t.url_type&&setTimeout((()=>window.open(t.url,"_blank")))},async refreshIndexData(){const{error:t,data:e,message:a}=await k();if(t)return void(await w({title:a||this.$t("home.text.interfaceError"),icon:"none"}));const{cmslist:i,ADlistNew:l,kuaixun:n,newslist:s}=e;this.cmslist=i,this.kuaixun=n,this.newslist=s,this.bannerList1=l.ad1,this.bannerList2=l.ad2,this.bannerList3=l.ad3},handleJumpPzIndex(t){x({url:`/pages/pz-module/index?index=${t}`})},handleDownloadApp(){window.open(this.appConfig.appdown,"_blank")},onSkipPageFn(t){1===t.url_type?1===t.url_level?y({url:t.url}):2===t.url_level&&x({url:t.url}):2===t.url_type&&window.open(t.url,"_blank")}},watch:{appConfig:{handler(t){t.web_site_title&&C({title:t.web_site_title}),this.platform&&"app"===this.platform.toLowerCase()&&(this.downloadAppShow=!1)},immediate:!0}}},[["render",function(t,e,a,i,l,n){const s=U,o=H,r=B,d=L,c=W(v("up-icon"),R),u=J,f=W(v("up-image"),K),h=W(v("up-tabs"),Q),m=M,p=W(v("up-modal"),q);return b(),T(d,{class:I(["index-page",{"index-page-padding":0===l.bannerList1.length}])},{default:S((()=>[l.bannerList1.length?(b(),T(r,{key:0,class:"muis-image",circular:"","indicator-dots":!1,autoplay:"",interval:3e3,duration:500},{default:S((()=>[(b(!0),$(A,null,j(l.bannerList1,((t,e)=>(b(),T(o,{key:e,onClick:e=>n.onSkipPageFn(t)},{default:S((()=>[P(s,{src:t.img_url,style:{width:"100%"}},null,8,["src"])])),_:2},1032,["onClick"])))),128))])),_:1})):N("",!0),P(d,{class:"header-search",onClick:n.navigateToSearch},{default:S((()=>[P(d,{class:"header-search-siz"}),P(d,{class:"text"},{default:S((()=>[P(c,{name:"search",size:"32rpx",color:"#fff",style:{"margin-right":"4rpx"}}),D(" "+F(t.$t("home.text.searchInput")),1)])),_:1})])),_:1},8,["onClick"]),P(d,{class:"bars-box"},{default:S((()=>[(b(!0),$(A,null,j(l.homeOperation,(t=>(b(),T(d,{key:t.name,onClick:e=>n.operationJunk(t)},{default:S((()=>[P(s,{style:{width:"42upx",height:"46upx"},src:t.img_url},null,8,["src"]),P(u,null,{default:S((()=>[D(F(t.name),1)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1}),l.bannerList3.length?(b(),T(r,{key:1,class:"muis-images",circular:"","indicator-dots":"true","indicator-color":"rgb(246,246,246)","indicator-active-color":"#fff",autoplay:"",interval:2e3,duration:500},{default:S((()=>[(b(!0),$(A,null,j(l.bannerList3,((t,e)=>(b(),T(o,{key:e,onClick:e=>n.onSkipPageFn(t)},{default:S((()=>[P(s,{src:t.img_url,mode:""},null,8,["src"])])),_:2},1032,["onClick"])))),128))])),_:1})):N("",!0),P(d,{class:"notice-box"},{default:S((()=>[z("strong",null,F(t.$t("home.text.announcement")),1),P(r,{class:"notice-swiper",circular:"",autoplay:"",vertical:""},{default:S((()=>[(b(!0),$(A,null,j(l.cmslist,(t=>(b(),T(o,{key:t.id},{default:S((()=>[P(d,{class:"swiper-item uni-bg-red",style:{},onClick:e=>n.navigateToArticleDetail({id:t.id})},{default:S((()=>[D(F(t.title),1)])),_:2},1032,["onClick"])])),_:2},1024)))),128))])),_:1}),P(u,{onClick:n.navigateToUserCenterStationMessageNotice},{default:S((()=>[D(F(t.$t("home.text.more")),1)])),_:1},8,["onClick"])])),_:1}),l.bannerList2.length?(b(),T(r,{key:2,class:"muis-images",circular:"","indicator-dots":"true","indicator-color":"rgb(246,246,246)","indicator-active-color":"#fff",autoplay:"",interval:2e3,duration:500},{default:S((()=>[(b(!0),$(A,null,j(l.bannerList2,((t,e)=>(b(),T(o,{key:e,onClick:e=>n.onSkipPageFn(t)},{default:S((()=>[P(s,{src:t.img_url,mode:""},null,8,["src"])])),_:2},1032,["onClick"])))),128))])),_:1})):N("",!0),l.daily?(b(),$(A,{key:3},["1"===l.daily.daily_welfare.activity.daily_attendance||"1"===l.daily.daily_welfare.activity.friend_invitation||"1"===l.daily.daily_welfare.activity.novice_gift_pack?(b(),T(d,{key:0,class:"welfare-box"},{default:S((()=>[P(d,{class:"welfare-header"},{default:S((()=>[D(F(l.daily.daily_welfare.daily_welfare_title),1)])),_:1}),P(d,{class:"welfare-content"},{default:S((()=>["1"===l.daily.daily_welfare.activity.daily_attendance||"1"===l.daily.daily_welfare.activity.friend_invitation||"1"===l.daily.daily_welfare.activity.novice_gift_pack?(b(),T(d,{key:0,class:"list",onClick:n.navigateToIndexActivityCenter},{default:S((()=>[P(d,null,{default:S((()=>[P(d,{class:"title"},{default:S((()=>[D(F(t.$t("home.text.activityCenter")),1)])),_:1}),P(d,null,{default:S((()=>[D(F(t.$t("home.text.participate")),1)])),_:1}),P(d,{class:"btn"},{default:S((()=>[D(F(t.$t("home.text.getInvolvedNow")),1)])),_:1})])),_:1}),P(s,{mode:"widthFix",style:{width:"170upx",bottom:"10rpx","z-index":"0",right:"8rpx"},src:"/fy/assets/benefit-icon1-AHkCKix5.png"})])),_:1},8,["onClick"])):N("",!0),"1"===l.daily.daily_welfare.daily_attendance?(b(),T(d,{key:1,class:"list",onClick:n.navigateToIndexCheckInCenter},{default:S((()=>[P(d,null,{default:S((()=>[P(d,{class:"title"},{default:S((()=>[D(F(t.$t("home.text.checkInCenter")),1)])),_:1}),P(d,null,{default:S((()=>[D(F(t.$t("home.text.continuous")),1)])),_:1}),P(d,{class:"btn"},{default:S((()=>[D(F(t.$t("home.text.checkInNow")),1)])),_:1})])),_:1}),P(s,{mode:"widthFix",style:{width:"100upx"},src:"/fy/assets/benefit-icon2-BNpyv1dn.png"})])),_:1},8,["onClick"])):N("",!0)])),_:1})])),_:1})):N("",!0)],64)):N("",!0),P(d,{class:"news-box"},{default:S((()=>[P(d,{class:"news-header"},{default:S((()=>[P(d,null,{default:S((()=>[P(f,{src:"/static/images/jie.png",width:"105rpx",height:"44rpx"}),P(u,{class:"news-head-tse"},{default:S((()=>[D(F(t.$t("home.text.liveAnalysis")),1)])),_:1})])),_:1}),P(u,{onClick:e[0]||(e[0]=t=>n.navigateToIndexNews({origin:1}))},{default:S((()=>[D(F(t.$t("home.text.more")),1)])),_:1})])),_:1}),P(d,{class:"market-list-box"},{default:S((()=>[(b(!0),$(A,null,j(l.kuaixun,(t=>(b(),T(d,{class:"list-box",key:t.id},{default:S((()=>[P(d,{class:"icon-box"}),P(d,null,{default:S((()=>[P(d,{class:"info"},{default:S((()=>[D(F(t.rich_text),1)])),_:2},1024),P(d,{class:"time"},{default:S((()=>[D(F(t.update_time),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128))])),_:1})])),_:1}),P(d,{class:"hot-news-box"},{default:S((()=>[P(d,{class:"hot-news-header"},{default:S((()=>[P(h,{list:l.tabs,current:l.tabCurrent,lineColor:"red",onChange:e[1]||(e[1]=t=>l.tabCurrent=t),lineWidth:"60",lineHeight:"8rpx",activeStyle:{color:"#303133",fontWeight:"bold",transform:"scale(1.05)"},inactiveStyle:{color:"#606266",transform:"scale(1)"}},null,8,["list","current","activeStyle","inactiveStyle"]),P(d,{onClick:e[2]||(e[2]=t=>n.navigateToIndexNews({origin:1===l.tabCurrent?1:0}))},{default:S((()=>[D(F(t.$t("home.text.more")),1)])),_:1})])),_:1}),E(P(d,{class:"news-content-box"},{default:S((()=>[(b(!0),$(A,null,j(l.newslist,(t=>(b(),T(d,{class:"list",key:t.id,onClick:e=>n.navigateToArticleDetail({id:t.id})},{default:S((()=>[P(d,null,{default:S((()=>[P(d,{class:"info"},{default:S((()=>[D(F(t.title),1)])),_:2},1024),P(d,{class:"time"},{default:S((()=>[D(F(t.time),1)])),_:2},1024)])),_:2},1024),P(s,{mode:"aspectFill",style:{width:"158upx","max-height":"112upx"},src:t.cj_thumbs},null,8,["src"])])),_:2},1032,["onClick"])))),128))])),_:1},512),[[O,0===l.tabCurrent]]),E(P(d,{class:"news-content-box"},{default:S((()=>[(b(!0),$(A,null,j(l.kuaixun,(t=>(b(),T(d,{class:"list",key:t.id,onClick:e=>n.navigateToArticleDetail({id:t.id,type:2})},{default:S((()=>[P(d,null,{default:S((()=>[P(d,{class:"info"},{default:S((()=>[D(F(t.short_rich_text),1)])),_:2},1024),P(d,{class:"time"},{default:S((()=>[D(F(t.update_time),1)])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1},512),[[O,1===l.tabCurrent]])])),_:1}),P(p,{show:l.modalShow,width:"650rpx",title:"",class:"waptc-modal-box",onCancel:e[4]||(e[4]=t=>l.modalShow=!1),onConfirm:e[5]||(e[5]=t=>l.modalShow=!1),onClose:e[6]||(e[6]=t=>l.modalShow=!1),showConfirmButton:!1},{default:S((()=>[P(d,{class:"slot-content"},{default:S((()=>[P(d,{class:"img-box"},{default:S((()=>[P(d,null,{default:S((()=>[D(F(t.$t("home.text.noticeTitle")),1)])),_:1}),P(s,{mode:"widthFix",style:{width:"100%",position:"absolute",bottom:"0",left:"0"},src:"/fy/assets/ann-wave-B76Uc-Cf.png"})])),_:1}),P(d,{style:{padding:"24upx"}},{default:S((()=>[P(m,{nodes:l.modalContent,class:"mun-is"},null,8,["nodes"])])),_:1}),P(d,{class:"modal-footer"},{default:S((()=>[P(d,{class:"btn",onClick:e[3]||(e[3]=t=>l.modalShow=!1)},{default:S((()=>[D(F(t.$t("home.text.confirmText")),1)])),_:1})])),_:1})])),_:1})])),_:1},8,["show"])])),_:1},8,["class"])}],["__scopeId","data-v-69655aef"]]);export{Y as default};
