var domainName = document.currentScript.getAttribute("domainname")
let currentScriptElement = document.currentScript
let liveChatFlowload = false

// Main Live Chat
const mainLiveChatDiv = document.createElement('div')
mainLiveChatDiv.className = "mainLiveChatDiv"

// For the Shadow Root
const bodyElement = mainLiveChatDiv.attachShadow({ mode: 'open' });

// will add the Bootstrap Styles
var BootstrapStyles = document.createElement("link");
BootstrapStyles.rel = "stylesheet";
BootstrapStyles.type = "text/css";
BootstrapStyles.href = `${domainName}/build/assets/plugins/bootstrap/css/bootstrap.css`;
bodyElement.appendChild(BootstrapStyles);

//will add the web-socket.js file .
var script = document.createElement("script");
script.setAttribute('domainName',domainName)
script.src = `${domainName}/build/assets/plugins/livechat/web-socket.js`;
script.setAttribute("wsport",currentScriptElement.getAttribute("wsport"))
script.defer = true;

// Will Add the Styles file.
var link = document.createElement("link");
link.rel = "stylesheet";
link.type = "text/css";
link.href = `${domainName}/build/assets/plugins/livechat/livechat.css`;


link.onload = function() {
    script.onload = function() {

        // Which will use to get the API data
        const getDataAPI = async(endPoint)=>{
            const responce = await fetch(`${domainName}/livechat/${endPoint}`)
            let data = await responce.json()
            return data
        }

        // Which use to Post the data to API
        const postDataAPI = async(postdata,endPoint)=>{
            const url = `${domainName}/livechat/${endPoint}`;
            let data = await fetch(url, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-Csrf-Token': 'N7J5vyQ9AcmVQW9dA2n4AJV1OcWzJ4pW2umV0QoI',
                    'X-Requested-With' : 'XMLHttpRequest',
                    'Accept':'application/json, text/javascript, */*; q=0.01',
                },
                body: JSON.stringify(postdata),
            }).then(response => response.text())

            return data
        }



        // Adding the chat Popup Button
        const popupButton = document.createElement("a");
        popupButton.id = "chat-popup"
        popupButton.className = "chat-popup-active"
        popupButton.onclick = ()=>{
            chatMessagePopup.classList.add("active")

            if(localStorage.LiveChatCust){
                // For the Message Seen Indication
                postDataAPI(JSON.parse(localStorage.LiveChatCust),'user-seen-messages-indication')

                // For the customer's online Indaction
                postDataAPI({custID : JSON.parse(localStorage.LiveChatCust).id},'customer-online').then((ele)=>{

                })
            }

            // To Remove the Unread Index Number
            popupButton.querySelector('.unreadIndexNumberPopup').innerText = ""
            popupButton.querySelector('.unreadIndexNumberPopup').classList.add("d-none")

            if(liveChatFlowload){
                bodyElement.querySelector('.direct-chat-messages').appendChild(basedOnTimeMessageConversationFlow())
            }

            // To scroll Down Chat
            bodyElement.querySelector(".popup-messages").scrollBy(0, bodyElement.querySelector(".popup-messages").scrollHeight)

            // to update the height of the first message
            bodyElement.querySelector(".popup-messages").style.marginBlockStart = `${bodyElement.querySelector(".offline-msg")?.clientHeight}px`


        }
        popupButton.innerHTML = `
        <i class="feather feather-message-square"></i>
        <span class="position-absolute top-0 start-100 d-none translate-middle badge rounded-pill bg-danger unreadIndexNumberPopup"></span>
        `
        bodyElement.appendChild(popupButton);

        // Adding the chat Message popup

        const chatMessagePopup = document.createElement("div")
        chatMessagePopup.className = "chat-message-popup card mb-4 animated"
        chatMessagePopup.innerHTML =  `<div class="popup-head">
                                            <div class="row">
                                                <div class="message-popup-left">
                                                    <div class="dropdown">
                                                        <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow" style="">
                                                            <a class="dropdown-item dropdownCloseBtn" href="javascript:void(0);">
                                                                <i class="fe fe-thumbs-up text-primary me-1"></i> Close
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 fw-normal">
                                                    Chat With Us
                                                </div>
                                                <div class="message-popup-right text-end">
                                                    <a class="popup-minimize-normal" href="javascript:void(0);"><i class="fe fe-chevron-down text-white"></i></a>
                                                    <a class="feedBackBtn d-none" href="javascript:void(0);"><i class="fe fe-x text-white"></i></a>
                                                    <a class="popup-minimize" href="javascript:void(0);"><i class="fe fe-x text-white"></i></a>
                                                    <a class="popup-minimize-fullscreen" href="javascript:void(0);"><i class="fe fe-x text-white"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="popup-chat-main-body">

                                        </div>`
        bodyElement.appendChild(chatMessagePopup)

        // To remove the chat Message Popup
        chatMessagePopup.querySelector('.popup-minimize-normal').onclick = ()=>{
            chatMessagePopup.classList.remove("active")
        }

        chatMessagePopup.querySelector('.dropdownCloseBtn').onclick = ()=>{
            chatMessagePopup.classList.remove("active")
        }

        // Which use to add the chat Body Content
        const chatBody = (htmlData,noNeedFullRefresh=true)=>{
            if(noNeedFullRefresh){
                chatMessagePopup.querySelector(".popup-chat-main-body").innerHTML = htmlData
            }
        }

         // For the feedBackForm Form
        const feedBackForm = (data,livechatdata)=>{
            data = data.split(",")
            let feedBackFormData = document.createElement("div")
            feedBackFormData.className = 'rating-chat-main-body'
            feedBackFormData.innerHTML = `
				<div class="p-3">
					<div class="text-start">
                    <button type="button" class="btn btn-primary btn-sm py-0 mb-2 downloadChart">Download Chat</button>
                    <button type="button" class="btn btn-secondary btn-sm py-0 mb-2 emailChat">Email Chat</button>
						<h5 class="font-weight-bold fs-20">Thank you for Contacting Us</h5>
						<h6>Please rate our supportive team in the following areas </h6>
						<form class="mt-4">
                        <div class="mt-0">
								<label>What is your best reason for your score <span class="text-red">*</span></label>
                                <div class="star-ratings start-ratings-main mb-2 mt-1  clearfix">
                                <svg class="ratingIcon "  xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" id="star"><path d="M22,10.1c0.1-0.5-0.3-1.1-0.8-1.1l-5.7-0.8L12.9,3c-0.1-0.2-0.2-0.3-0.4-0.4C12,2.3,11.4,2.5,11.1,3L8.6,8.2L2.9,9
                                C2.6,9,2.4,9.1,2.3,9.3c-0.4,0.4-0.4,1,0,1.4l4.1,4l-1,5.7c0,0.2,0,0.4,0.1,0.6c0.3,0.5,0.9,0.7,1.4,0.4l5.1-2.7l5.1,2.7
                                c0.1,0.1,0.3,0.1,0.5,0.1l0,0c0.1,0,0.1,0,0.2,0c0.5-0.1,0.9-0.6,0.8-1.2l-1-5.7l4.1-4C21.9,10.5,22,10.3,22,10.1z"></path></svg>

                                <svg class="ratingIcon "  xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" id="star"><path d="M22,10.1c0.1-0.5-0.3-1.1-0.8-1.1l-5.7-0.8L12.9,3c-0.1-0.2-0.2-0.3-0.4-0.4C12,2.3,11.4,2.5,11.1,3L8.6,8.2L2.9,9
                                C2.6,9,2.4,9.1,2.3,9.3c-0.4,0.4-0.4,1,0,1.4l4.1,4l-1,5.7c0,0.2,0,0.4,0.1,0.6c0.3,0.5,0.9,0.7,1.4,0.4l5.1-2.7l5.1,2.7
                                c0.1,0.1,0.3,0.1,0.5,0.1l0,0c0.1,0,0.1,0,0.2,0c0.5-0.1,0.9-0.6,0.8-1.2l-1-5.7l4.1-4C21.9,10.5,22,10.3,22,10.1z"></path></svg>

                                <svg class="ratingIcon "  xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" id="star"><path d="M22,10.1c0.1-0.5-0.3-1.1-0.8-1.1l-5.7-0.8L12.9,3c-0.1-0.2-0.2-0.3-0.4-0.4C12,2.3,11.4,2.5,11.1,3L8.6,8.2L2.9,9
                                    C2.6,9,2.4,9.1,2.3,9.3c-0.4,0.4-0.4,1,0,1.4l4.1,4l-1,5.7c0,0.2,0,0.4,0.1,0.6c0.3,0.5,0.9,0.7,1.4,0.4l5.1-2.7l5.1,2.7
                                    c0.1,0.1,0.3,0.1,0.5,0.1l0,0c0.1,0,0.1,0,0.2,0c0.5-0.1,0.9-0.6,0.8-1.2l-1-5.7l4.1-4C21.9,10.5,22,10.3,22,10.1z"></path></svg>

                                <svg class="ratingIcon"  xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" id="star"><path d="M22,10.1c0.1-0.5-0.3-1.1-0.8-1.1l-5.7-0.8L12.9,3c-0.1-0.2-0.2-0.3-0.4-0.4C12,2.3,11.4,2.5,11.1,3L8.6,8.2L2.9,9
                                C2.6,9,2.4,9.1,2.3,9.3c-0.4,0.4-0.4,1,0,1.4l4.1,4l-1,5.7c0,0.2,0,0.4,0.1,0.6c0.3,0.5,0.9,0.7,1.4,0.4l5.1-2.7l5.1,2.7
                                c0.1,0.1,0.3,0.1,0.5,0.1l0,0c0.1,0,0.1,0,0.2,0c0.5-0.1,0.9-0.6,0.8-1.2l-1-5.7l4.1-4C21.9,10.5,22,10.3,22,10.1z"></path></svg>

                                <svg class="ratingIcon"  xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" id="star"><path d="M22,10.1c0.1-0.5-0.3-1.1-0.8-1.1l-5.7-0.8L12.9,3c-0.1-0.2-0.2-0.3-0.4-0.4C12,2.3,11.4,2.5,11.1,3L8.6,8.2L2.9,9
                                    C2.6,9,2.4,9.1,2.3,9.3c-0.4,0.4-0.4,1,0,1.4l4.1,4l-1,5.7c0,0.2,0,0.4,0.1,0.6c0.3,0.5,0.9,0.7,1.4,0.4l5.1-2.7l5.1,2.7
                                    c0.1,0.1,0.3,0.1,0.5,0.1l0,0c0.1,0,0.1,0,0.2,0c0.5-0.1,0.9-0.6,0.8-1.2l-1-5.7l4.1-4C21.9,10.5,22,10.3,22,10.1z"></path></svg>

                                </div>
							</div>
							<div class="mt-0">
								<label>Your problem has been rectified <span class="text-red">*</span></label>
								<div class="star-ratings start-ratings-main my-2 clearfix">
									<div class="stars stars-example-fontawesome star-sm">

                                    <div class="form-check ps-0">
                                        ${
                                            data.map((option,index)=>{
                                                return(
                                                    `<input type="radio" class="rating-fontawesome" name="rating" value='${option}' id="flexRadioDefault${index}">
                                                    <label for="flexRadioDefault${index}">
                                                        ${option}
                                                    </label><br/>`
                                                )
                                            }).join('')
                                        }

                                    </div>

									</div>
								</div>
							</div>
							<div class="mt-3">
								<label>Could you please provide any additional feedback for us</label>
								<textarea class="form-control mt-2 feedBackData" rows="5" cols="50" placeholder="Type Here..."></textarea>
							</div>
							<button disabled type="button" class="btn btn-success px-5 mt-4 btn-chat-close submitFeedBackBtn" href="javascript:void(0);">Submit your Review</button>
						</form>
					</div>
				</div>
            `
            // Rating click Function
            feedBackFormData.querySelectorAll(".ratingIcon").forEach((star, index)=>{
                star.addEventListener('click', function() {
                    feedBackFormData.querySelectorAll(".ratingIcon").forEach(s => s.classList.remove('checked'));
                    for (let i = 0; i <= index; i++) {
                      feedBackFormData.querySelectorAll(".ratingIcon")[i].classList.add('checked');
                    }
                    if(feedBackFormData.querySelector(".rating-fontawesome:checked").value){
                        feedBackFormData.querySelector(".rating-fontawesome:checked").checked = false
                    }else{
                        feedBackFormData.querySelector(".submitFeedBackBtn").disabled = true
                    }
                  });
            })
            // feed back Form submit
            feedBackFormData.querySelector(".submitFeedBackBtn").onclick = ()=>{
                let feedBackData = {
                    starRating : feedBackFormData.querySelectorAll(".ratingIcon.checked").length,
                    problemRectified : feedBackFormData.querySelector(".rating-fontawesome:checked").value,
                    feedBackData : feedBackFormData.querySelector(".feedBackData").value
                }

                let data = {
                    message :JSON.stringify(feedBackData),
                    username : liveChatCust.username,
                    id : liveChatCust.id,
                    customerId :liveChatCust.id,
                    messageType: "feedBack"

                }
                postDataAPI(data,'broadcast-message').then((res)=>{
                    chatBody(messageConversation())
                    chatMessagePopup.classList.remove("rating-section-body")
                    bodyElement.querySelector(".feedBackBtn").classList.add('d-none')
                    // To Scroll Down the Conversation
                    bodyElement.querySelector(".popup-messages").scrollBy(0, bodyElement.querySelector(".popup-messages").scrollHeight)
                })
            }
            // To append the feedBackFormData
            if(!chatMessagePopup.querySelector(".rating-chat-main-body")){
                chatMessagePopup.appendChild(feedBackFormData)
                bodyElement.querySelector(".feedBackData").oninput = (sdfsdf=>{
                    if(bodyElement.querySelectorAll(".ratingIcon.checked").length && bodyElement.querySelector(".feedBackData").value){
                        bodyElement.querySelector(".submitFeedBackBtn").disabled = false
                    }else{
                        bodyElement.querySelector(".submitFeedBackBtn").disabled = true
                    }
                })
            }

            function downloadTextFile(filename, textContent) {
                const blob = new Blob([textContent], { type: 'text/plain' });
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = filename;
                link.click();
                URL.revokeObjectURL(url);
            }

            // For the Text file DownLoad
            const filename = 'liveChatConversation.txt';
            const modifiedData = livechatdata.map(item => `${item.created_at} - ${item.livechat_username} - ${item.message}`);
            const textContent = JSON.stringify(modifiedData, null, 4);
            feedBackFormData.querySelector(".downloadChart").onclick = ()=>{
                downloadTextFile(filename, textContent);
            }

            // Send email the text file
            feedBackFormData.querySelector(".emailChat").onclick = ()=>{
                let userInfo = JSON.parse(localStorage.LiveChatCust)
                const formData = new FormData();
                formData.append('file', new Blob([textContent], { type: 'text/plain' }), filename);
                formData.append('email', userInfo?.email);


                fetch(`${domainName}/livechat/livechat-download-file`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Csrf-Token': 'N7J5vyQ9AcmVQW9dA2n4AJV1OcWzJ4pW2umV0QoI',
                        'X-Requested-With' : 'XMLHttpRequest',
                        'Accept':'application/json, text/javascript, */*; q=0.01',
                    },
                }).then((res)=>res.json()).then(data=>{
                    alert(data.message)
                })

            }

        }

        let FlowChatConversation = []

        // Welcome Form
        const welcomeForm = (onFlowmessage,offlineSendMessage) => {

            // To Get The IP
            let ipAddress = []
            let geolocationPermission = false
            fetch('https://ipinfo.io/json')
            .then(response => response.json())
            .then(data => {
                ipAddress = data
            })
            .catch((error) =>{
                console.error('Error fetching IP address:', error)
                ipAddress.ip = 'null'
                ipAddress.city  = 'null'
                ipAddress.region  = 'null'
                ipAddress.timezone  = 'null'
            });

            // To getting the Country Name
            function getCountryName(countryCode) {
                const countryMapping = {
                    "BD": "Bangladesh",
                    "BE": "Belgium",
                    "BF": "Burkina Faso",
                    "BG": "Bulgaria",
                    "BA": "Bosnia and Herzegovina",
                    "BB": "Barbados",
                    "WF": "Wallis and Futuna",
                    "BL": "Saint Barthelemy",
                    "BM": "Bermuda",
                    "BN": "Brunei",
                    "BO": "Bolivia",
                    "BH": "Bahrain",
                    "BI": "Burundi",
                    "BJ": "Benin",
                    "BT": "Bhutan",
                    "JM": "Jamaica",
                    "BV": "Bouvet Island",
                    "BW": "Botswana",
                    "WS": "Samoa",
                    "BQ": "Bonaire, Saint Eustatius and Saba ",
                    "BR": "Brazil",
                    "BS": "Bahamas",
                    "JE": "Jersey",
                    "BY": "Belarus",
                    "BZ": "Belize",
                    "RU": "Russia",
                    "RW": "Rwanda",
                    "RS": "Serbia",
                    "TL": "East Timor",
                    "RE": "Reunion",
                    "TM": "Turkmenistan",
                    "TJ": "Tajikistan",
                    "RO": "Romania",
                    "TK": "Tokelau",
                    "GW": "Guinea-Bissau",
                    "GU": "Guam",
                    "GT": "Guatemala",
                    "GS": "South Georgia and the South Sandwich Islands",
                    "GR": "Greece",
                    "GQ": "Equatorial Guinea",
                    "GP": "Guadeloupe",
                    "JP": "Japan",
                    "GY": "Guyana",
                    "GG": "Guernsey",
                    "GF": "French Guiana",
                    "GE": "Georgia",
                    "GD": "Grenada",
                    "GB": "United Kingdom",
                    "GA": "Gabon",
                    "SV": "El Salvador",
                    "GN": "Guinea",
                    "GM": "Gambia",
                    "GL": "Greenland",
                    "GI": "Gibraltar",
                    "GH": "Ghana",
                    "OM": "Oman",
                    "TN": "Tunisia",
                    "JO": "Jordan",
                    "HR": "Croatia",
                    "HT": "Haiti",
                    "HU": "Hungary",
                    "HK": "Hong Kong",
                    "HN": "Honduras",
                    "HM": "Heard Island and McDonald Islands",
                    "VE": "Venezuela",
                    "PR": "Puerto Rico",
                    "PS": "Palestinian Territory",
                    "PW": "Palau",
                    "PT": "Portugal",
                    "SJ": "Svalbard and Jan Mayen",
                    "PY": "Paraguay",
                    "IQ": "Iraq",
                    "PA": "Panama",
                    "PF": "French Polynesia",
                    "PG": "Papua New Guinea",
                    "PE": "Peru",
                    "PK": "Pakistan",
                    "PH": "Philippines",
                    "PN": "Pitcairn",
                    "PL": "Poland",
                    "PM": "Saint Pierre and Miquelon",
                    "ZM": "Zambia",
                    "EH": "Western Sahara",
                    "EE": "Estonia",
                    "EG": "Egypt",
                    "ZA": "South Africa",
                    "EC": "Ecuador",
                    "IT": "Italy",
                    "VN": "Vietnam",
                    "SB": "Solomon Islands",
                    "ET": "Ethiopia",
                    "SO": "Somalia",
                    "ZW": "Zimbabwe",
                    "SA": "Saudi Arabia",
                    "ES": "Spain",
                    "ER": "Eritrea",
                    "ME": "Montenegro",
                    "MD": "Moldova",
                    "MG": "Madagascar",
                    "MF": "Saint Martin",
                    "MA": "Morocco",
                    "MC": "Monaco",
                    "UZ": "Uzbekistan",
                    "MM": "Myanmar",
                    "ML": "Mali",
                    "MO": "Macao",
                    "MN": "Mongolia",
                    "MH": "Marshall Islands",
                    "MK": "Macedonia",
                    "MU": "Mauritius",
                    "MT": "Malta",
                    "MW": "Malawi",
                    "MV": "Maldives",
                    "MQ": "Martinique",
                    "MP": "Northern Mariana Islands",
                    "MS": "Montserrat",
                    "MR": "Mauritania",
                    "IM": "Isle of Man",
                    "UG": "Uganda",
                    "TZ": "Tanzania",
                    "MY": "Malaysia",
                    "MX": "Mexico",
                    "IL": "Israel",
                    "FR": "France",
                    "IO": "British Indian Ocean Territory",
                    "SH": "Saint Helena",
                    "FI": "Finland",
                    "FJ": "Fiji",
                    "FK": "Falkland Islands",
                    "FM": "Micronesia",
                    "FO": "Faroe Islands",
                    "NI": "Nicaragua",
                    "NL": "Netherlands",
                    "NO": "Norway",
                    "NA": "Namibia",
                    "VU": "Vanuatu",
                    "NC": "New Caledonia",
                    "NE": "Niger",
                    "NF": "Norfolk Island",
                    "NG": "Nigeria",
                    "NZ": "New Zealand",
                    "NP": "Nepal",
                    "NR": "Nauru",
                    "NU": "Niue",
                    "CK": "Cook Islands",
                    "XK": "Kosovo",
                    "CI": "Ivory Coast",
                    "CH": "Switzerland",
                    "CO": "Colombia",
                    "CN": "China",
                    "CM": "Cameroon",
                    "CL": "Chile",
                    "CC": "Cocos Islands",
                    "CA": "Canada",
                    "CG": "Republic of the Congo",
                    "CF": "Central African Republic",
                    "CD": "Democratic Republic of the Congo",
                    "CZ": "Czech Republic",
                    "CY": "Cyprus",
                    "CX": "Christmas Island",
                    "CR": "Costa Rica",
                    "CW": "Curacao",
                    "CV": "Cape Verde",
                    "CU": "Cuba",
                    "SZ": "Swaziland",
                    "SY": "Syria",
                    "SX": "Sint Maarten",
                    "KG": "Kyrgyzstan",
                    "KE": "Kenya",
                    "SS": "South Sudan",
                    "SR": "Suriname",
                    "KI": "Kiribati",
                    "KH": "Cambodia",
                    "KN": "Saint Kitts and Nevis",
                    "KM": "Comoros",
                    "ST": "Sao Tome and Principe",
                    "SK": "Slovakia",
                    "KR": "South Korea",
                    "SI": "Slovenia",
                    "KP": "North Korea",
                    "KW": "Kuwait",
                    "SN": "Senegal",
                    "SM": "San Marino",
                    "SL": "Sierra Leone",
                    "SC": "Seychelles",
                    "KZ": "Kazakhstan",
                    "KY": "Cayman Islands",
                    "SG": "Singapore",
                    "SE": "Sweden",
                    "SD": "Sudan",
                    "DO": "Dominican Republic",
                    "DM": "Dominica",
                    "DJ": "Djibouti",
                    "DK": "Denmark",
                    "VG": "British Virgin Islands",
                    "DE": "Germany",
                    "YE": "Yemen",
                    "DZ": "Algeria",
                    "US": "United States",
                    "UY": "Uruguay",
                    "YT": "Mayotte",
                    "UM": "United States Minor Outlying Islands",
                    "LB": "Lebanon",
                    "LC": "Saint Lucia",
                    "LA": "Laos",
                    "TV": "Tuvalu",
                    "TW": "Taiwan",
                    "TT": "Trinidad and Tobago",
                    "TR": "Turkey",
                    "LK": "Sri Lanka",
                    "LI": "Liechtenstein",
                    "LV": "Latvia",
                    "TO": "Tonga",
                    "LT": "Lithuania",
                    "LU": "Luxembourg",
                    "LR": "Liberia",
                    "LS": "Lesotho",
                    "TH": "Thailand",
                    "TF": "French Southern Territories",
                    "TG": "Togo",
                    "TD": "Chad",
                    "TC": "Turks and Caicos Islands",
                    "LY": "Libya",
                    "VA": "Vatican",
                    "VC": "Saint Vincent and the Grenadines",
                    "AE": "United Arab Emirates",
                    "AD": "Andorra",
                    "AG": "Antigua and Barbuda",
                    "AF": "Afghanistan",
                    "AI": "Anguilla",
                    "VI": "U.S. Virgin Islands",
                    "IS": "Iceland",
                    "IR": "Iran",
                    "AM": "Armenia",
                    "AL": "Albania",
                    "AO": "Angola",
                    "AQ": "Antarctica",
                    "AS": "American Samoa",
                    "AR": "Argentina",
                    "AU": "Australia",
                    "AT": "Austria",
                    "AW": "Aruba",
                    "IN": "India",
                    "AX": "Aland Islands",
                    "AZ": "Azerbaijan",
                    "IE": "Ireland",
                    "ID": "Indonesia",
                    "UA": "Ukraine",
                    "QA": "Qatar",
                    "MZ": "Mozambique"
                };

                return countryMapping[countryCode?.toUpperCase()] || 'Unknown Country';
            }

            // To get the User Browser Name
            function getBrowserName() {
                if (typeof navigator.userAgentData !== 'undefined') {
                  // Use navigator.userAgentData if available (Chrome, Edge, Opera)
                  return navigator.userAgentData.brands[navigator.userAgentData.brands.length - 1].brand;
                } else {
                  // Fallback to parsing the User-Agent string
                  const userAgent = navigator.userAgent;

                  let browserName;

                  if (userAgent.match(/Firefox\//i)) {
                    browserName = "Firefox";
                  } else if (userAgent.match(/Chrome\//i)) {
                    browserName = "Chrome";
                  } else if (userAgent.match(/Edge\//i)) {
                    browserName = "Edge";
                  } else if (userAgent.match(/Safari\//i)) {
                    browserName = "Safari";
                  } else if (userAgent.match(/Opera\//i)) {
                    browserName = "Opera";
                  } else {
                    browserName = "Unknown";
                  }

                  return browserName;
                }
            }

            // To get the geolocation data
            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition(getLoc,errorlog)
            }

            function getLoc(data){
                geolocationPermission = true
                const latitude = data.coords.latitude;
                const longitude = data.coords.longitude;

                // Construct the Nominatim API URL
                const apiUrl = `https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`;

                // Make a request to the API
                fetch(apiUrl)
                  .then(response => response.json())
                  .then(data => {
                    if (data.display_name) {
                      const address = data.display_name;
                      geolocationPermission = address
                    } else {
                      console.error('Unable to retrieve address.');
                    }
                  })
                  .catch(error => {
                    console.error('Error fetching data from Nominatim API:', error);
                  });
            }

            function errorlog(data) {
                geolocationPermission = false
            }

            // To send the form data to DB
            myFunction = ()=>{
                // To disabled the submit Button
                bodyElement.querySelector("#chatUserdata [type='button']").disabled = true
                let Userdata = bodyElement.querySelector("#chatUserdata")
                const formData = new FormData(Userdata);
                var name = formData.get('name');
                var email = formData.get('email');
                var mobile = formData.get('mobilenumber');

                if (!name || !email || !mobile) {
                    alert('Please fill out all fields.');
                    bodyElement.querySelector("#chatUserdata [type='button']").disabled = false
                    return;
                }

                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    alert('Please enter a valid email address.');
                    bodyElement.querySelector("#chatUserdata [type='button']").disabled = false
                    return;
                }


                let postData
                if(geolocationPermission){
                    postData = {
                        "name" : formData.get("name"),
                        "email" : formData.get("email"),
                        "mobilenumber" : formData.get("mobilenumber"),
                        "browserAndOSInfo" : getBrowserName(),
                        "flowChatMessages" : JSON.stringify(FlowChatConversation),
                        "fullAddress" : location.href,
                        "loginIp" : ipAddress.ip,
                        "city" : null,
                        "state" : null,
                        "timezone" : null,
                        "country" : geolocationPermission
                    }
                }else{
                    postData = {
                        "name" : formData.get("name"),
                        "email" : formData.get("email"),
                        "mobilenumber" : formData.get("mobilenumber"),
                        "browserAndOSInfo" : getBrowserName(),
                        "flowChatMessages" : JSON.stringify(FlowChatConversation),
                        "fullAddress" : location.href,
                        "loginIp" : ipAddress.ip,
                        "city" : ipAddress.city,
                        "state" : ipAddress.region,
                        "timezone" : ipAddress.timezone,
                        "country" : getCountryName(ipAddress.country)
                    }
                }

                let processData = postDataAPI(postData,'customerdata')

                processData.then(data=>{
                    let responceData = JSON.parse(data)
                    if(responceData.success){
                        localStorage.setItem("LiveChatCust",JSON.stringify(responceData.custdata))
                        // TO Add the customer Online
                        postDataAPI({custID : JSON.parse(localStorage.LiveChatCust).id},'customer-online').then((ele)=>{

                        })

                        // To send the First Message
                        if(onFlowmessage){
                            let firstMessageData = {
                                message : onFlowmessage,
                                username : responceData.custdata.username,
                                id : responceData.custdata.id,
                                customerId :responceData.custdata.id

                            }
                            // To Add the first Message
                            postDataAPI(firstMessageData,'broadcast-message').then(subdata=>{
                                // To Send the Offline Message
                                if(offlineSendMessage){
                                    let liveChatCust = localStorage.LiveChatCust ? JSON.parse(localStorage.LiveChatCust) : []
                                    let welcomeMessagedata = {
                                        message :offlineSendMessage.errorMessage,
                                        username : liveChatCust.username,
                                        id : liveChatCust.id,
                                        customerId :liveChatCust.id,
                                        messageType: "welcomeMessage"

                                    }
                                    postDataAPI(welcomeMessagedata,'broadcast-message').then((ele)=>{
                                        chatBody(messageConversation())
                                    })
                                }else{
                                    chatBody(messageConversation())
                                }
                            })
                        }
                    }
                })

                processData.catch(error=>{
                    console.log("error",error);
                })
            }

            // To submit the form when the user presses the Enter key
            setTimeout(() => {
                bodyElement.getElementById("chatUserdata").addEventListener("keydown", function(event) {
                    if (event.key === "Enter") {
                        event.preventDefault(); // Prevent the default form submission
                        bodyElement.querySelector("#chatUserdata .wecomeFormSubmitBtn").click()
                    }
                });
            }, 1000);

            return (
                `
                    <div class="card" style="
                    height: 100vh;">
                        <div class="card-header border-bottom-0">
                            <h6 class="card-title">Verify User Details</h6>
                        </div>
                        <div class="card-body">
                            <form id="chatUserdata">
                                <div class="form-group mb-3">
                                    <input type="text" name="name" class="form-control" id="username" placeholder="Enter Full Name">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter email">
                                </div>
                                <div class="form-group">
                                    <input type="number" name="mobilenumber" class="form-control" id="mobile" placeholder="Enter Mobile Number">
                                </div>
                                <button type="button" onclick="myFunction()" class="btn btn-primary mt-4 mb-0 wecomeFormSubmitBtn">Submit</button>
                            </form>
                        </div>
                    </div>
                `
            )
        }

        // To add the all Message conversation
        let liveChatCust = localStorage.LiveChatCust ? JSON.parse(localStorage.LiveChatCust) : []
        const messageConversation = ()=>{
            // to Update the liveChatCust
            liveChatCust = localStorage.LiveChatCust ? JSON.parse(localStorage.LiveChatCust) : []

            let OfflineMessagePermission = false

            sendMessage = ()=>{
                afterMessageSend = false
                // To send the Typing Indication After message send
                setTimeout(() => {
                    afterMessageSend = true
                }, 500);

                const now = new Date();
                const hours = now.getHours();
                const minutes = now.getMinutes();
                const period = hours >= 12 ? "PM" : "AM";

                const formattedTime = `${((hours + 11) % 12) + 1}:${minutes}${period}`;

                if(bodyElement.querySelector("#status_message").value.trim()){
                    let data = {
                        message :bodyElement.querySelector("#status_message").value,
                        username : liveChatCust.username,
                        id : liveChatCust.id,
                        customerId :liveChatCust.id

                    }
                    postDataAPI(data,'broadcast-message').then((ele)=>{
                        // For the Offline Message Send

                        var allDirectChatMsgs = bodyElement.querySelectorAll('.direct-chat-msg');
                        var filteredDirectChatMsgs = Array.from(allDirectChatMsgs).filter(function(element) {
                            return !element.classList.contains('right');
                        });
                        var lastDirectChatMsg = filteredDirectChatMsgs[filteredDirectChatMsgs.length - 1];

                        if(OfflineMessagePermission && lastDirectChatMsg.innerText.replace(/\b\d{1,2}:\d{2}[APMapm]{2}\b/, '').trim() != OfflineMessagePermission.errorMessage.trim()){
                            bodyElement.querySelector('.direct-chat-messages').appendChild(OfflineMessageIndication(OfflineMessagePermission.errorMessage))
                            bodyElement.querySelector(".popup-messages").scrollBy(0, bodyElement.querySelector(".popup-messages").scrollHeight)
                        }
                    })

                    let directChatMessages = bodyElement.querySelector(".direct-chat-messages")
                    let custMessage = document.createElement("div");
                    custMessage.className = "direct-chat-msg right"
                    custMessage.innerHTML = `
                        <div class="direct-chat-text">
                        ${bodyElement.querySelector("#status_message").value}
                        <small class="time-text">${formattedTime}</small>
                        </div>
                    `

                    directChatMessages.appendChild(custMessage)
                    bodyElement.querySelector(".popup-messages").scrollBy(0, bodyElement.querySelector(".popup-messages").scrollHeight)
                    bodyElement.querySelector("#status_message").value = ""
                }
            }

            function formatTime(inputTime) {
                const date = new Date(inputTime);

                const hours = date.getHours();
                const minutes = date.getMinutes();
                const ampm = hours >= 12 ? 'PM' : 'AM';
                const formattedHours = hours % 12 === 0 ? 12 : hours % 12;
                const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;

                const formattedTime = `${formattedHours}:${formattedMinutes}${ampm}`;
                return formattedTime;
            }

            // For the Chat data
            function formatDateString(inputDateStr) {
                const inputDate = new Date(inputDateStr);
                const monthNames = [
                            "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                    ];
                const year = inputDate.getFullYear();
                const month = monthNames[inputDate.getMonth()];
                const day = inputDate.getDate();
                const formattedDate = `${day},${month} ${year}`;

                return formattedDate;
            }

            let customerMessage = (data)=>{
                let custLi = document.createElement("div");
                custLi.className = "direct-chat-msg right"
                custLi.innerHTML = `
                    <div class="direct-chat-text">
                    ${data.message_type == "image" ? `
                    <img class="imageMessageLiveChat"
                    imagesrc="${data.message}"
                    src="${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ? data.message : `${domainName}/build/assets/images/svgs/file.svg`}"
                    class="d-block" alt="img" style="${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ? '' : 'height: 5rem;'}">
                    ` : data.message_type == "feedBack" ? `Your feedback has been submitted` : `${data.message}`}
                        <small class="time-text">${formatTime(data.created_at)}</small>
                    </div>
                `

                return custLi
            }

            let AgentMessage = (data)=>{
                let agentLi = document.createElement("div");
                agentLi.className = "direct-chat-msg"
                agentLi.innerHTML = `
                    <div class="direct-chat-text" style="${data.message_type == "image" ? 'text-align: center;' : ''}">
                    ${data.message_type == "image" ? `
                     <img class="imageMessageLiveChat"
                     imagesrc="${data.message}"
                     src="${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ? data.message : `${domainName}/build/assets/images/svgs/file.svg`}"
                     class="d-block" alt="img" style="${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ? '' : 'height: 5rem;'}">
                    ` : `${data.message}`}

                    <small class="time-text">${formatTime(data.created_at)}</small></div>
                `

                return agentLi
            }

            let conversationDiv = document.createElement("div");
            conversationDiv.className = "direct-chat-messages"
            conversationDiv.style.overflow = "hidden"

            getDataAPI(`singlecustdata/${JSON.parse(localStorage.LiveChatCust).id}`).then((data)=>{

                if(data.nocustomerdatafound){
                    chatBody(messageConversationFlow())
                    localStorage.removeItem("LiveChatCust")
                    return false
                }

                // To modify the size of the Live Chat icon.
                if(data.livechatcust.livechatIconSize == "large"){
                    popupButton.classList.add("chat-popup-lg")
                }else{
                    popupButton.classList.remove("chat-popup-lg")
                }

                // To Change the live Chat Position
                if(data.livechatcust.livechatPosition == "left"){
                    chatMessagePopup.classList.add("chat-message-popup-right")
                    popupButton.classList.add("chat-popup-right")
                }else{
                    chatMessagePopup.classList.remove("chat-message-popup-right")
                    popupButton.classList.remove("chat-popup-right")
                }

                // To add the FeedBack Click Event
                if(data.livechatcust.engage_conversation != "" && data.livechatcust.engage_conversation){
                    chatMessagePopup.querySelector(".feedBackBtn").classList.remove("d-none")
                    chatMessagePopup.querySelector(".feedBackBtn").onclick = ()=>{
                        chatMessagePopup.classList.add("rating-section-body")
                        feedBackForm(data.livechatcust.livechatFeedbackDropdown,data.livechatdata)
                    }
                }


                // For the Chat Flow Created Date
                let currentDate = null;


                // For the Chat Flow Messages
                if(data.livechatcust.chat_flow_messages){
                    const messageDate = formatDateString(data.livechatcust.created_at);

                    conversationDiv.innerHTML += `
                                    <div class="chat-box-single-line">
                                        <abbr class="timestamp">${messageDate}</abbr>
                                    </div>
                                `;
                    currentDate = messageDate;

                    JSON.parse(data.livechatcust.chat_flow_messages).map((flowMes)=>{
                        let updatedFlowMes = flowMes
                        updatedFlowMes.created_at = data.livechatcust.created_at
                        if(flowMes.authMessage == "agent"){
                            conversationDiv.appendChild(AgentMessage(updatedFlowMes))
                        }else{
                            conversationDiv.appendChild(customerMessage(updatedFlowMes))
                        }
                    })
                }

                let unreadIndexNumber = 0

                // For the Messages append
                data.livechatdata.map((chatdata)=>{
                    const messageDate = formatDateString(chatdata.created_at);
                    if (messageDate !== currentDate){
                        conversationDiv.innerHTML += `
                            <div class="chat-box-single-line">
                                <abbr class="timestamp">${messageDate}</abbr>
                            </div>
                        `;
                        // For the date of the Chat
                        currentDate = messageDate;
                    }{
                        if(!chatdata.livechat_cust_id && chatdata.status != "comment"){
                            conversationDiv.appendChild(AgentMessage(chatdata))
                        }else{
                            if(chatdata.status != "comment"){
                                conversationDiv.appendChild(customerMessage(chatdata))
                            }
                        }
                    }

                    // To get the get the unread index number
                    if(chatdata.livechat_user_id && chatdata.status != "comment" && chatdata.status != "seen"){
                        unreadIndexNumber = unreadIndexNumber + 1
                    }
                })

                // To add the Unread Index Number
                if(unreadIndexNumber){
                    popupButton.querySelector('.unreadIndexNumberPopup').innerText = unreadIndexNumber
                    popupButton.querySelector('.unreadIndexNumberPopup').classList.remove('d-none')
                }

                // To show the LiveChat include Users informaction
                if(data.livechatcust.engage_conversation){
                    let livechatInfo = document.createElement('div')
                        livechatInfo.innerHTML = `
                        <div class="avatar-list avatar-list-stacked d-flex flex-nowrap"></div>
                        <div class="ms-4 infoNamesText"></div>
                        `
                    let onlineUserNames = []
                    JSON.parse(data.livechatcust.engage_conversation).map((user,index)=>{
                        let userSpan = document.createElement('span')
                        userSpan.className = `avatar brround`
                        if(user.image){
                            userSpan.style.backgroundImage = `url('${domainName}/uploads/profile/${user.image}')`
                        }else{
                            userSpan.style.backgroundImage = `url('${domainName}/uploads/profile/user-profile.png')`
                        }
                        if(index+1 <= 2){
                            livechatInfo.querySelector(".avatar-list-stacked").appendChild(userSpan)
                        }
                        onlineUserNames.push(user.name)
                    })
                    if(JSON.parse(data.livechatcust.engage_conversation).length > 2){
                        let userSpan = document.createElement('span')
                        userSpan.className = `avatar brround`
                        userSpan.innerHTML = `+${JSON.parse(data.livechatcust.engage_conversation).length - 2}`
                        livechatInfo.querySelector(".avatar-list-stacked").appendChild(userSpan)
                    }

                    // For the online user names
                    livechatInfo.querySelector('.infoNamesText').innerHTML = `
                        <h6 class="mb-0 font-weight-bold text-truncate">${onlineUserNames.length == 1 ? onlineUserNames[0] : data.livechatcust.LivechatCustWelcomeMsg}</h6>
                    `

                    if(bodyElement.querySelector(".livechatInfo")){
                        bodyElement.querySelector(".livechatInfo").innerHTML = livechatInfo.innerHTML
                    }
                }

                // For No One joined At the time to show the online users info
                if(!data.livechatcust.engage_conversation && data.livechatcust.onlineUsers && data.livechatcust.onlineUsers.value){
                     let livechatInfo = document.createElement('div')
                        livechatInfo.innerHTML = `
                        <div class="avatar-list avatar-list-stacked d-flex flex-nowrap"></div>
                        <div class="ms-4 infoNamesText"></div>
                        `
                        let onlineUserNames = []
                        JSON.parse(data.livechatcust.onlineUsers.value).map((user,index)=>{
                            let userSpan = document.createElement('span')
                            userSpan.className = `avatar brround`
                            if(user.image){
                                userSpan.style.backgroundImage = `url('${domainName}/uploads/profile/${user.image}')`
                            }else{
                                userSpan.style.backgroundImage = `url('${domainName}/uploads/profile/user-profile.png')`
                            }
                            if(index+1 <= 2){
                                let onlineSpan = document.createElement('span')
                                onlineSpan.className = "avatar-status bg-green"
                                userSpan.appendChild(onlineSpan)
                                livechatInfo.querySelector(".avatar-list-stacked").appendChild(userSpan)
                            }
                            onlineUserNames.push(user.name)
                        })
                        if(JSON.parse(data.livechatcust.onlineUsers.value).length > 2){
                            let userSpan = document.createElement('span')
                            userSpan.className = `avatar brround`
                            userSpan.innerHTML = `+${JSON.parse(data.livechatcust.onlineUsers.value).length - 2}`
                            livechatInfo.querySelector(".avatar-list-stacked").appendChild(userSpan)
                        }

                        // For the online user names
                        livechatInfo.querySelector('.infoNamesText').innerHTML = `
                            <h6 class="mb-0 font-weight-bold text-truncate">${onlineUserNames.length == 1 ? onlineUserNames[0] : data.livechatcust.LivechatCustWelcomeMsg}</h6>
                        `

                        if(bodyElement.querySelector(".livechatInfo")){
                            bodyElement.querySelector(".livechatInfo").innerHTML = livechatInfo.innerHTML
                        }
                }

                // if the messages already present. At the time the messages will not add
                let ConversationBodyElement = bodyElement.querySelector(".popup-messages")
                if(!ConversationBodyElement.children.length){
                    ConversationBodyElement.appendChild(conversationDiv);
                    // To scroll Down the Chat
                    bodyElement.querySelector(".popup-messages").scrollBy(0, bodyElement.querySelector(".popup-messages").scrollHeight)


                    // To add the Image Viewer
                    bodyElement.querySelectorAll(".imageMessageLiveChat").forEach((element)=>{
                        element.parentElement.onclick = ()=>{
                            window.open(element.getAttribute('imagesrc'))
                        }
                    })

                    // To check the last Message time cross the 24 Hours
                    if(data.livechatdata[0]){
                        let welcomeMessages = data.livechatdata.filter(item => item.message_type === "welcomeMessage");
                        let createdAtTimeArray = welcomeMessages.length > 0 ? welcomeMessages[welcomeMessages.length - 1].created_at : data.livechatdata[0].created_at
                        if (createdAtTimeArray) {
                            let lastWelcomeMessageCreatedAt = createdAtTimeArray
                            const creationTimeString = lastWelcomeMessageCreatedAt;
                            const creationTime = new Date(creationTimeString);
                            const currentTime = new Date();
                            const timeDifference = currentTime - creationTime;
                            const hoursDifference = timeDifference / (1000 * 60 * 60);
                            if(data.livechatcust.liveChatFlowload == "every-24-hours" && hoursDifference >= 24){
                                liveChatFlowload = true
                            }

                        }
                    }

                }

                // If it is Offline
                if(data.livechatcust.isonlineoroffline == "offline" && data.livechatcust.OfflineStatusMessage){
                    OfflineMessagePermission = { errorMessage: data.livechatcust.OfflineMessage}
                    // To Add the Offline Message Status
                    let statusInfoMessage = bodyElement.querySelector(".infoNamesText")
                    let infoMessage = document.createElement('small')
                    infoMessage.classList.add('offline-msg')
                    infoMessage.classList.add('offline-infor')
                    infoMessage.innerHTML = `<span class="w-2 h-2 brround bg-secondary d-inline-block me-1"></span>`+data.livechatcust.OfflineStatusMessage
                    if(!bodyElement.querySelector(".infoNamesText small") && statusInfoMessage){
                        statusInfoMessage.appendChild(infoMessage)
                    }else{
                        bodyElement.querySelector(".infoNamesText small").innerHTML = `<span class="w-2 h-2 brround bg-secondary d-inline-block me-1"></span>`+data.livechatcust.OfflineStatusMessage
                    }
                }

                // If it is Online
                if(data.livechatcust.isonlineoroffline == "online" && data.livechatcust.OnlineStatusMessage){
                    // To Add the Online Message Status
                    let statusInfoMessage = bodyElement.querySelector(".infoNamesText")
                    let infoMessage = document.createElement('small')
                    infoMessage.classList.add('offline-msg')
                    infoMessage.innerHTML = `<span class="w-2 h-2 brround bg-success d-inline-block me-1"></span>`+data.livechatcust.OnlineStatusMessage
                    if(!bodyElement.querySelector(".infoNamesText small") && statusInfoMessage){
                        statusInfoMessage.appendChild(infoMessage)
                    }else{
                        bodyElement.querySelector(".infoNamesText small").innerHTML = `<span class="w-2 h-2 brround bg-success d-inline-block me-1"></span>`+data.livechatcust.OnlineStatusMessage
                    }
                }

                // Chat File Upload
                if(!data.livechatcust.file_upload_permission){
                    bodyElement.querySelector(".liveChatFileUpload").style.display = "none"
                }else{
                    bodyElement.querySelector(".liveChatFileUpload").style.display = ""

                }

                // For the LiveChat Image Upload
                bodyElement.querySelector("#chat-file-upload").onchange = ()=>{

                    var fileInput = bodyElement.querySelector("#chat-file-upload");
                    var file = fileInput.files[0];
                    fileInput.value = ''
                    var ThereIsError = false

                    if(file){

                        const chatMsgElements = bodyElement.querySelectorAll('.direct-chat-msg.right');
                        const lastTwoElements = Array.from(chatMsgElements).slice(-data.livechatcust.livechatMaxFileUpload);

                        if(!data.livechatcust.file_upload_permission){
                            ThereIsError = { errorMessage: "You are Not Having File Upload Permission " };
                        }else if (file.size > parseInt(data.livechatcust.livechatFileUploadMax) * 1024 * 1024) {
                            ThereIsError = { errorMessage: `File size exceeds ${data.livechatcust.livechatFileUploadMax} MB. Please choose a smaller file.` };
                        }else if (data.livechatcust.livechatFileUploadTypes && !data.livechatcust.livechatFileUploadTypes.split(',').some(ext => file.name.toLowerCase().endsWith(ext.toLowerCase().trim()))) {
                            ThereIsError = { errorMessage: `Invalid file extension. Please choose a file with ${data.livechatcust.livechatFileUploadTypes} extension(s).` };
                        }else if(lastTwoElements.every(element => element.querySelector('img'))){
                            ThereIsError = { errorMessage: `The maximum file upload limit has been exceeded.` };
                        }
                        else{
                            ThereIsError = false
                        }

                        // For add the Upload indication
                        let uploadingIndication = document.createElement("div")
                        uploadingIndication.className = "direct-chat-msg right"
                        uploadingIndication.id = "uploadingIndication"
                        uploadingIndication.innerHTML = `
                                <div class="direct-chat-text">
                                    uploading...
                                </div>
                        `
                        if(!ThereIsError){
                            bodyElement.querySelector(".direct-chat-messages").appendChild(uploadingIndication)
                            bodyElement.querySelector(".popup-messages").scrollBy(0, bodyElement.querySelector(".popup-messages").scrollHeight)
                        }



                        if(!ThereIsError){
                            var formData = new FormData();
                            formData.append('chatFileUpload', file);

                            const now = new Date();
                            const hours = now.getHours();
                            const minutes = now.getMinutes();
                            const period = hours >= 12 ? "PM" : "AM";

                            const formattedTime = `${((hours + 11) % 12) + 1}:${minutes}${period}`;


                            fetch(`${domainName}/livechat/live-chat-image-upload`, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Csrf-Token': 'N7J5vyQ9AcmVQW9dA2n4AJV1OcWzJ4pW2umV0QoI',
                                    'X-Requested-With' : 'XMLHttpRequest',
                                    'Accept':'application/json, text/javascript, */*; q=0.01',
                                },
                            })
                            .then(response =>{
                                return response.json()
                            })
                            .then(resdata => {
                                let data = {
                                    message :`${domainName}/public/uploads/livechat/${resdata.uploadedfilename}`,
                                    username : liveChatCust.username,
                                    id : liveChatCust.id,
                                    customerId :liveChatCust.id,
                                    messageType: "image"

                                }
                                postDataAPI(data,'broadcast-message')

                                let directChatMessages = bodyElement.querySelector(".direct-chat-messages")
                                let custMessage = document.createElement("div");
                                custMessage.className = "direct-chat-msg right"
                                custMessage.innerHTML = `
                                    <div class="direct-chat-text">
                                    <img class="imageMessageLiveChat" imagesrc="${data.message}"
                                    src="${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ? data.message : `${domainName}/build/assets/images/svgs/file.svg`}"
                                    style="${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ? '' : 'height: 5rem;'}"
                                    />
                                    <small class="time-text">${formattedTime}</small>
                                    </div>
                                `

                                directChatMessages.querySelector("#uploadingIndication").remove()
                                directChatMessages.appendChild(custMessage)

                                // To add the Image Viwer
                                bodyElement.querySelectorAll(".imageMessageLiveChat").forEach((element)=>{
                                    element.parentElement.onclick = ()=>{
                                        window.open(element.getAttribute('imagesrc'))
                                    }
                                })

                                bodyElement.querySelector(".popup-messages").scrollBy(0, bodyElement.querySelector(".popup-messages").scrollHeight)
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                        }else{
                            alert(ThereIsError.errorMessage)
                        }
                    }

                }


                // Offline no need to display chat
                if(!parseInt(data.livechatcust.offlineDisplayLiveChat) && data.livechatcust.isonlineoroffline == "offline"){
                    chatMessagePopup.remove()
                    popupButton.remove()
                }

                // To remove the LiveChat
                if(data.livechatcust.liveChatHidden == "true"){
                    chatMessagePopup.remove()
                    popupButton.remove()
                }

                // To remove the live chat online indaction
                const beforeUnloadHandler = (event)=>{
                    postDataAPI({custID : data.livechatcust.id},'remove-customer-online').then((ele)=>{

                    })
                    setTimeout(() => {
                        if(bodyElement.querySelector(".chat-message-popup").classList.contains("active")){
                            postDataAPI({custID : JSON.parse(localStorage.LiveChatCust).id},'customer-online').then((ele)=>{

                            })
                        }
                    }, 3000);
                    event.returnValue = "Write something clever here.."
                }

                // Adding beforeunload event To Livechat BTN
                bodyElement.querySelector("#chat-popup").addEventListener("click",()=>{
                    window.addEventListener('beforeunload', beforeUnloadHandler)
                })

                // TO Remove the beforeunload event To Livechat Close BTN
                bodyElement.querySelector(".popup-minimize-normal").addEventListener("click",()=>{
                    window.removeEventListener('beforeunload', beforeUnloadHandler)
                    postDataAPI({custID : data.livechatcust.id},'remove-customer-online').then((ele)=>{

                    })
                })

                // To remove the online in the initial state
                if(!bodyElement.querySelector(".chat-message-popup").classList.contains('active')){
                    postDataAPI({custID : data.livechatcust.id},'remove-customer-online').then((ele)=>{

                    })
                }

            })

            // Typing
            var debounceTimeout;
            var afterMessageSend = true
            customerTyping = (ele)=>{
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(function() {
                    if(afterMessageSend){
                        textAreaChanged(ele);
                    }
                  }, 500);
            }

            function textAreaChanged(textarea) {
                let data = {
                    message :null,
                    username : liveChatCust.username,
                    id : null,
                    customerId :liveChatCust.id,
                    typingMessage : textarea.value

                }
                postDataAPI(data,'broadcast-message-typing')
            }

            // Enter Message Send Function
            handleKeyDown = (event)=>{
                if (event.key === 'Enter' && !event.shiftKey) {
                    event.preventDefault();
                    sendMessage()
                }
            }

            return (
                `
                <div class="user-header p-3 border-top border-bottom">
                        <div class="d-flex livechatInfo align-items-center">

                        </div>
                </div>
                <div class="popup-messages pt-0">

                </div>
                <div class="popup-messages-footer card-footer p-0">
                        <textarea oninput="customerTyping(this)" onkeydown="handleKeyDown(event)" id="status_message" placeholder="Type a message..." rows="10" cols="40" name="message" class="form-control"></textarea>
                        <div class="chat-footer-icons">
                            <a class="liveChatFileUpload" href="javascript:void(0);" onclick="{bodyElement.querySelector('#chat-file-upload').click()}"><i class="fe fe-paperclip text-muted"></i></a>
                            <input type="file" id="chat-file-upload" class="d-none" name="chat-file-upload" autocomplete="off">
                            <a class="" href="javascript:void(0);" onclick="sendMessage()"><i class="fe fe-send text-muted"></i></a>
                        </div>
                </div>
                `
            )
        }

        // Message flow conversation
        const messageConversationFlow = ()=>{
            let flowChatData

            let OfflineMessagePermission = false

            // Getting the chat Flow data
            getDataAPI(`flow/${currentScriptElement.getAttribute('testitout') ? currentScriptElement.getAttribute('testitout') : null}`).then((data)=>{
                if(data.success){

                    // To modify the size of the Live Chat icon.
                    if(data.success.livechatIconSize == "large"){
                        popupButton.classList.add("chat-popup-lg")
                    }else{
                        popupButton.classList.remove("chat-popup-lg")
                    }

                    // To Change the live Chat Position
                    if(data.success.livechatPosition == "left"){
                        chatMessagePopup.classList.add("chat-message-popup-right")
                        popupButton.classList.add("chat-popup-right")
                    }else{
                        chatMessagePopup.classList.remove("chat-message-popup-right")
                        popupButton.classList.remove("chat-popup-right")
                    }

                    flowChatData = data.success.liveChatFlow ? JSON.parse(data.success.liveChatFlow).nodes : null

                    // To shoe the first Welcome Message
                    if(data.success.liveChatFlow && JSON.parse(data.success.liveChatFlow).nodes['1'].name == "Welcome Message" && !bodyElement.querySelector(".popup-messages")?.children.length){
                        AgentMessage(JSON.parse(data.success.liveChatFlow).nodes['1'])
                    }

                    // To show the LiveChat Info informaction
                    if(data.success.onlineUsers.value){
                        let livechatInfo = document.createElement('div')
                        livechatInfo.innerHTML = `
                        <div class="avatar-list avatar-list-stacked d-flex flex-nowrap"></div>
                        <div class="ms-4 infoNamesText"></div>
                        `
                        let onlineUserNames = []
                        JSON.parse(data.success.onlineUsers.value).map((user,index)=>{
                            let userSpan = document.createElement('span')
                            userSpan.className = `avatar brround`
                            if(user.image){
                                userSpan.style.backgroundImage = `url('${domainName}/uploads/profile/${user.image}')`
                            }else{
                                userSpan.style.backgroundImage = `url('${domainName}/uploads/profile/user-profile.png')`
                            }
                            if(index+1 <= 2){
                                let onlineSpan = document.createElement('span')
                                onlineSpan.className = "avatar-status bg-green"
                                userSpan.appendChild(onlineSpan)
                                livechatInfo.querySelector(".avatar-list-stacked").appendChild(userSpan)
                            }
                            onlineUserNames.push(user.name)
                        })
                        if(JSON.parse(data.success.onlineUsers.value).length > 2){
                            let userSpan = document.createElement('span')
                            userSpan.className = `avatar brround`
                            userSpan.innerHTML = `+${JSON.parse(data.success.onlineUsers.value).length - 2}`
                            livechatInfo.querySelector(".avatar-list-stacked").appendChild(userSpan)
                        }

                        // For the online user names
                        livechatInfo.querySelector('.infoNamesText').innerHTML = `
                            <h6 class="mb-0 font-weight-bold text-truncate">${onlineUserNames.length == 1 ? onlineUserNames[0] : data.success.LivechatCustWelcomeMsg}</h6>
                        `

                        if(bodyElement.querySelector(".livechatInfo")){
                            bodyElement.querySelector(".livechatInfo").innerHTML = livechatInfo.innerHTML
                        }
                    }else{
                        let livechatInfo = document.createElement('div')
                        livechatInfo.innerHTML = `
                        <div class="avatar-list avatar-list-stacked d-flex flex-nowrap"></div>
                        <div class="ms-4 infoNamesText"></div>
                        `
                        // For the online user names
                        livechatInfo.querySelector('.infoNamesText').innerHTML = `
                            <h6 class="mb-0 font-weight-bold text-truncate">No Online Agent</h6>
                        `
                        if(bodyElement.querySelector(".livechatInfo")){
                            bodyElement.querySelector(".livechatInfo").innerHTML = livechatInfo.innerHTML
                        }
                    }

                    // If it is Online
                    if(data.success.isonlineoroffline == "online" && data.success.OnlineStatusMessage){
                        // To Add the Online Message Status
                        let statusInfoMessage = bodyElement.querySelector(".infoNamesText")
                        let infoMessage = document.createElement('small')
                        infoMessage.classList.add('offline-msg')
                        infoMessage.innerHTML = `<span class="w-2 h-2 brround bg-success d-inline-block me-1"></span>`+data.success.OnlineStatusMessage
                        if(!bodyElement.querySelector(".infoNamesText small") && statusInfoMessage){
                            statusInfoMessage.appendChild(infoMessage)
                        }else{
                            bodyElement.querySelector(".infoNamesText small").innerHTML = `<span class="w-2 h-2 brround bg-success d-inline-block me-1"></span>`+data.success.OnlineStatusMessage
                        }
                    }

                    // If it is Offline
                    if(data.success.isonlineoroffline == "offline" && data.success.OfflineStatusMessage){
                        OfflineMessagePermission = { errorMessage: data.success.OfflineMessage}
                        // To Add the Offline Message Status
                        let statusInfoMessage = bodyElement.querySelector(".infoNamesText")
                        let infoMessage = document.createElement('small')
                        infoMessage.classList.add('offline-msg')
                        infoMessage.classList.add('offline-infor')
                        infoMessage.innerHTML = `<span class="w-2 h-2 brround bg-secondary d-inline-block me-1"></span>`+data.success.OfflineStatusMessage
                        if(!bodyElement.querySelector(".infoNamesText small") && statusInfoMessage){
                            statusInfoMessage.appendChild(infoMessage)
                        }else{
                            if(bodyElement.querySelector(".infoNamesText small")){
                                bodyElement.querySelector(".infoNamesText small").innerHTML = `<span class="w-2 h-2 brround bg-secondary d-inline-block me-1"></span>`+data.success.OfflineStatusMessage
                            }
                        }
                    }

                    // For Offline remove liveChat
                    if(data.success.isonlineoroffline == "offline" && !parseInt(data.success.offlineDisplayLiveChat)){
                        chatMessagePopup.remove()
                        popupButton.remove()
                    }

                    // To remove the liveCht
                    if(data.success.liveChatHidden == "true"){
                        chatMessagePopup.remove()
                        popupButton.remove()
                    }

                }
            })

            // Agent Message div
            let AgentMessage = (data)=>{

                // For the message div
                let agentLi = document.createElement("div");
                agentLi.className = "direct-chat-msg"
                agentLi.innerHTML = `
                                <div class="direct-chat-text">${data.data.text}</div>
                `

                // For the Option buttons
                let liveChatOptionBtn = document.createElement("div")
                liveChatOptionBtn.className = "d-flex flex-wrap liveChatOptionBtn"

                // connections node Loop
                data.outputs.act.connections.map((connectedNode)=>{
                    let optionNode = document.createElement("div")
                    optionNode.onclick = ()=>{
                        // To remove the Options
                        bodyElement.querySelector(".liveChatOptionBtn").remove()

                        // Append the selected option as a customer message
                        bodyElement.querySelector(".popup-messages").appendChild(customerMessage(flowChatData[connectedNode.node]))

                        // to loop the messages and options
                        if(flowChatData[connectedNode.node].inputs.text.connections[0]){
                            AgentMessage(flowChatData[flowChatData[connectedNode.node].inputs.text.connections[0].node])
                        }
                    }
                    optionNode.innerHTML = `<button
                    style="background-image: none;
                    border-color: #0d6efd;"
                    class="btn btn-outline-primary rounded-pill m-1 shadow-none">${flowChatData[connectedNode.node].data.optionName}</button>`
                    liveChatOptionBtn.appendChild(optionNode)
                })

                // For the Message
                bodyElement.querySelector(".popup-messages")?.appendChild(agentLi);

                // For the Options
                bodyElement.querySelector(".popup-messages")?.appendChild(liveChatOptionBtn);
            }
            // Customer div
            let customerMessage = (data)=>{
                let custLi = document.createElement("div");
                custLi.className = "direct-chat-msg right"
                custLi.innerHTML = `
                                <div class="direct-chat-text">${data.data.optionName}</div>
                `
                return custLi
            }

            sendMessage = ()=>{
                if(bodyElement.querySelector("#status_message").value.length){
                    // For the Making Chat Flow Message Array
                    bodyElement.querySelectorAll(".popup-messages .direct-chat-msg").forEach((element)=>{
                        if(element.classList.contains('right')){
                            FlowChatConversation.push({
                                authMessage: 'cust',
                                message: element.querySelector(".direct-chat-text").innerText
                            })
                        }else{
                            FlowChatConversation.push({
                                authMessage: 'agent',
                                message: element.querySelector(".direct-chat-text").innerText
                            })
                        }
                    })

                    // To open The welcome Form
                    chatBody(welcomeForm(bodyElement.querySelector("#status_message").value,OfflineMessagePermission))
                }
            }

            // Enter Message Send Function
            handleKeyDown = (ele)=>{
                if (ele.key === 'Enter' && !ele.shiftKey) {
                    ele.preventDefault();
                    sendMessage()
                }
            }


            return (
                `
                    <div class="user-header p-3 border-top border-bottom">
                    <div class="d-flex livechatInfo align-items-center">
                    </div>
                    </div>
                    <div class="popup-messages pt-0">

                    </div>
                    <div class="popup-messages-footer card-footer p-0">
                            <textarea onkeydown="handleKeyDown(event)" id="status_message" placeholder="Type a message..." rows="10" cols="40" name="message" class="form-control"></textarea>
                            <div class="chat-footer-icons">
                                <a class="" href="javascript:void(0);" onclick="sendMessage()" ><i class="fe fe-send text-muted"></i></a>
                            </div>
                    </div>
                `
            )
        }

        // To add the Flow Message After the 24 Hours
        const basedOnTimeMessageConversationFlow = ()=>{
            let flowChatElement = document.createElement("div")
            flowChatElement.className = "basedOnTimeMessageConversationFlowDiv"
            let flowChatData

            // Getting the chat Flow data
            getDataAPI(`flow/null`).then((data)=>{
                if(data.success){
                    flowChatData = JSON.parse(data.success.liveChatFlow).nodes

                    // To shoe the first Welcome Message
                    if(JSON.parse(data.success.liveChatFlow).nodes['1'].name == "Welcome Message"){
                        AgentMessage(JSON.parse(data.success.liveChatFlow).nodes['1'])
                    }

                }
            })

            // Agent Message div
            let AgentMessage = (data)=>{

                // For the message div
                let agentLi = document.createElement("div");
                agentLi.className = "direct-chat-msg"
                agentLi.innerHTML = `
                                <div class="direct-chat-text">${data.data.text}</div>
                `

                // To send the Welcome Message as a message
                let welcomeMessagedata = {
                    message :data.data.text,
                    username : liveChatCust.username,
                    id : liveChatCust.id,
                    customerId :liveChatCust.id,
                    messageType: "welcomeMessage"

                }
                postDataAPI(welcomeMessagedata,'broadcast-message')

                // For the Option buttons
                let liveChatOptionBtn = document.createElement("div")
                liveChatOptionBtn.className = "d-flex flex-wrap liveChatOptionBtn"

                // connections node Loop
                data.outputs.act.connections.map((connectedNode)=>{
                    let optionNode = document.createElement("div")
                    optionNode.onclick = ()=>{
                        // To remove the Options
                        bodyElement.querySelector(".liveChatOptionBtn").remove()

                        // Append the selected option as a customer message
                        bodyElement.querySelector(".basedOnTimeMessageConversationFlowDiv").appendChild(customerMessage(flowChatData[connectedNode.node]))

                        // To send the Welcome Message as a message
                        let welcomeMessagedata = {
                            message :flowChatData[connectedNode.node].data.optionName,
                            username : liveChatCust.username,
                            id : liveChatCust.id,
                            customerId :liveChatCust.id,

                        }
                        postDataAPI(welcomeMessagedata,'broadcast-message').then((ele)=>{
                            // to loop the messages and options
                            if(flowChatData[connectedNode.node].inputs.text.connections[0]){
                                AgentMessage(flowChatData[flowChatData[connectedNode.node].inputs.text.connections[0].node])
                            }
                        })
                    }
                    optionNode.innerHTML = `<button
                    style="background-image: none;
                    border-color: #0d6efd;"
                    class="btn btn-outline-primary rounded-pill m-1 shadow-none">${flowChatData[connectedNode.node].data.optionName}</button>`
                    liveChatOptionBtn.appendChild(optionNode)
                })

                // For the Message
                flowChatElement.appendChild(agentLi);

                // For the Options
                flowChatElement.appendChild(liveChatOptionBtn);
            }

            // Customer div
            let customerMessage = (data)=>{

                let custLi = document.createElement("div");
                custLi.className = "direct-chat-msg right"
                custLi.innerHTML = `
                                <div class="direct-chat-text">${data.data.optionName}</div>
                `
                return custLi
            }

            return flowChatElement

        }

        // For the Offline Message Indication
        const OfflineMessageIndication = (message)=>{
            let offlineChatElement = document.createElement("div")
            offlineChatElement.className = "direct-chat-msg"

            // For the Corrent Time
            function getCurrentTime() {
                const currentDate = new Date();
                let hours = currentDate.getHours();
                let minutes = currentDate.getMinutes();
                const ampm = hours >= 12 ? 'PM' : 'AM';

                // Convert hours to 12-hour format
                hours = hours % 12 || 12;

                // Add leading zero to minutes if necessary
                minutes = minutes < 10 ? '0' + minutes : minutes;

                const currentTime = hours + ':' + minutes + ampm;
                return currentTime;
            }

            // To post The Message
            let welcomeMessagedata = {
                message :message,
                username : liveChatCust.username,
                id : liveChatCust.id,
                customerId :liveChatCust.id,
                messageType: "welcomeMessage"

            }
            postDataAPI(welcomeMessagedata,'broadcast-message')

            offlineChatElement.innerHTML = `
            <div class="direct-chat-text">
                ${message}
            <small class="time-text">${getCurrentTime()}</small>
            </div>
            `
            return offlineChatElement
        }

        // Adding the WellCome form
        if(!localStorage.LiveChatCust){
            chatBody(messageConversationFlow())
        }else{
            chatBody(messageConversation())
        }

        let debouncing
        let debouncing2
        let debouncing3

        // Public Socket
        Echo.channel('liveChat').listen('ChatMessageEvent',(socket)=>{

            // For the Online Users Update
            if(!socket.message && socket.onlineUserUpdated == 'true' && !localStorage.LiveChatCust){
                clearTimeout(debouncing)
                debouncing = setTimeout(() => {
                    chatBody(messageConversationFlow(),false)
                }, 1000);
            }

            // For the Engage Users Update
            if(!socket.message && (socket.onlineUserUpdated == 'true' || socket.engageUser) && localStorage.LiveChatCust){
                clearTimeout(debouncing3)
                debouncing3 = setTimeout(() => {
                    chatBody(messageConversation(),false)
                }, 3000);
            }

            // For the Message update
            if(localStorage.LiveChatCust){
                let liveChatCust = JSON.parse(localStorage.LiveChatCust)
                if(typeof(socket.customerId) == 'string' && socket.customerId == liveChatCust.id && socket.message){
                    const now = new Date();
                    const hours = now.getHours();
                    const minutes = now.getMinutes();
                    const period = hours >= 12 ? "PM" : "AM";

                    const formattedTime = `${((hours + 11) % 12) + 1}:${minutes}${period}`;

                    // To remove the Typing induction
                    if(bodyElement.querySelector("#typingIndication")){
                        bodyElement.querySelector("#typingIndication").remove()
                    }

                    let directChatMessages = bodyElement.querySelector(".direct-chat-messages")
                    let custMessage = document.createElement("div");
                    custMessage.className = "direct-chat-msg"
                    custMessage.innerHTML = `<div class="direct-chat-text">
                    ${socket.messageType == "image" ? `<img  imagesrc="${socket.message}" src="${socket.message.toLowerCase().endsWith(".jpg") || socket.message.toLowerCase().endsWith(".png") ? socket.message : `${domainName}/build/assets/images/svgs/file.svg`}"/>` :`${socket.message}`}
                    <small class="time-text">${formattedTime}</small></div>`

                    // To Open Image In the new Tab
                    if(custMessage.querySelector("img")){
                        custMessage.querySelector("img").onclick = ()=>{
                            window.open(custMessage.querySelector("img").getAttribute('imagesrc'));
                        }
                    }

                    directChatMessages.appendChild(custMessage)

                    // To Scroll Down the Conversation
                    bodyElement.querySelector(".popup-messages").scrollBy(0, bodyElement.querySelector(".popup-messages").scrollHeight)

                    // For the Message Seen Indication
                    if(chatMessagePopup.classList.contains('active')){
                        postDataAPI(JSON.parse(localStorage.LiveChatCust),'user-seen-messages-indication')
                    }

                    if(!chatMessagePopup.classList.contains('active') && popupButton.querySelector('.unreadIndexNumberPopup').classList.contains("d-none")){
                        popupButton.querySelector('.unreadIndexNumberPopup').innerText = "1"
                        popupButton.querySelector('.unreadIndexNumberPopup').classList.remove("d-none")
                    }else{
                        if(!chatMessagePopup.classList.contains('active') && !popupButton.querySelector('.unreadIndexNumberPopup').classList.contains("d-none")){
                            popupButton.querySelector('.unreadIndexNumberPopup').innerText = parseInt(popupButton.querySelector('.unreadIndexNumberPopup').innerText) + 1
                        }
                    }

                }
            }

            // For the Typing induction
            if(!socket.message && socket.agentInfo && socket.customerId == liveChatCust.id && socket.typingMessage){

                // To remove the Typing induction
                if(bodyElement.querySelector("#typingIndication")){
                    bodyElement.querySelector("#typingIndication").remove()
                }

                let directChatMessages = bodyElement.querySelector(".direct-chat-messages")
                let custMessage = document.createElement("div");
                custMessage.id = "typingIndication"
                custMessage.className = "direct-chat-msg"
                custMessage.innerHTML = `<div class="direct-chat-text">Typing....</div>`

                directChatMessages.appendChild(custMessage)

                // To Scroll Down the Conversation
                bodyElement.querySelector(".popup-messages").scrollBy(0, bodyElement.querySelector(".popup-messages").scrollHeight)

                clearTimeout(debouncing2);
                debouncing2 = setTimeout(function() {
                    if(bodyElement.querySelector("#typingIndication")){
                        bodyElement.querySelector("#typingIndication").remove()
                    }
                }, 5000);
            }

        })

    };
    bodyElement.appendChild(script);
}
bodyElement.appendChild(link);

// To add the Main Live Chat Div
document.body.appendChild(mainLiveChatDiv);
