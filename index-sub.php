

<style>
* {
  font-family: "Helvetica Neue", Helvetica;
  font-size: 15px;
  font-variant: normal;
  padding: 0;
  margin: 0;
}

html {
  height: 100%;
}

body {
  background: #E6EBF1;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100%;
}

form {
  width: 480px;
  margin: 20px 0;
}

.group {
  background: white;
  box-shadow: 0 7px 14px 0 rgba(49, 49, 93, 0.10), 0 3px 6px 0 rgba(0, 0, 0, 0.08);
  border-radius: 4px;
  margin-bottom: 20px;
}

label {
  position: relative;
  color: #8898AA;
  font-weight: 300;
  height: 40px;
  line-height: 40px;
  margin-left: 20px;
  display: flex;
  flex-direction: row;
}

.group label:not(:last-child) {
  border-bottom: 1px solid #F0F5FA;
}

label > span {
  width: 120px;
  text-align: right;
  margin-right: 30px;
}

.field {
  background: transparent;
  font-weight: 300;
  border: 0;
  color: #31325F;
  outline: none;
  flex: 1;
  padding-right: 10px;
  padding-left: 10px;
  cursor: text;
}

.field::-webkit-input-placeholder {
  color: #CFD7E0;
}

.field::-moz-placeholder {
  color: #CFD7E0;
}

button {
  float: left;
  display: block;
  background: #666EE8;
  color: white;
  box-shadow: 0 7px 14px 0 rgba(49, 49, 93, 0.10), 0 3px 6px 0 rgba(0, 0, 0, 0.08);
  border-radius: 4px;
  border: 0;
  margin-top: 20px;
  font-size: 15px;
  font-weight: 400;
  width: 100%;
  height: 40px;
  line-height: 38px;
  outline: none;
}

button:focus {
  background: #555ABF;
}

button:active {
  background: #43458B;
}

.outcome {
  float: left;
  width: 100%;
  padding-top: 8px;
  min-height: 24px;
  text-align: center;
}

.success,
.error {
  display: none;
  font-size: 13px;
}

.success.visible,
.error.visible {
  display: inline;
}

.error {
  color: #E4584C;
}

.success {
  color: #666EE8;
}

.success .token {
  font-weight: 500;
  font-size: 13px;
}

</style>
<html>
<head>
	<title>Stripe Demo </title>
	<script src="https://js.stripe.com/v3/"></script>
</head>


<body>
  <form action="process.php" method="POST">
  <input type="hidden" name="action" value="stripe"/>
  <input type="hidden" name="email" value="milan@gmail.com"/>
    <input type="hidden" name="token" />
    <div class="group">
      <label>
        <span>Card number</span>
        <div id="card-number-element" name= "card-number-element" class="field"></div>
      </label>
      <label>
        <span>Expiry date</span>
        <div id="card-expiry-element" name= "card-expiry-element" class="field"></div>
      </label>
      <label>
        <span>CVC</span>
        <div id="card-cvc-element" name="card-cvc-element" class="field"></div>
      </label>

     
      
    </div>
    <button type="submit">Pay</button>
    <div class="outcome">
      <div class="error"></div>
      <div class="success">
        Success! Your Stripe token is <span class="token"></span>
      </div>
    </div>
  </form>
</body>

<script>

var stripe = Stripe('pk_test_TId61EOz69XXZdbHGntIGULL');
var elements = stripe.elements();

var style = {
	base: {
	  iconColor: '#666EE8',
	  color: '#31325F',
	  lineHeight: '40px',
	  fontWeight: 300,
	  fontFamily: 'Helvetica Neue',
	  fontSize: '15px',
  
	  '::placeholder': {
		color: '#CFD7E0',
	  },
	},
  };
  
  var cardNumberElement = elements.create('cardNumber', {
	style: style
  });
  cardNumberElement.mount('#card-number-element');
  
  var cardExpiryElement = elements.create('cardExpiry', {
	style: style
  });
  cardExpiryElement.mount('#card-expiry-element');
  
  var cardCvcElement = elements.create('cardCvc', {
	style: style
  });
  cardCvcElement.mount('#card-cvc-element');
  
 
  
  
  function setOutcome(result) {
	var successElement = document.querySelector('.success');
	var errorElement = document.querySelector('.error');
	successElement.classList.remove('visible');
	errorElement.classList.remove('visible');
  
	if (result.token) {
	  // In this example, we're simply displaying the token
	  successElement.querySelector('.token').textContent = result.token.id;
	  successElement.classList.add('visible');
  
	  // In a real integration, you'd submit the form with the token to your backend server
	  var form = document.querySelector('form');
	  form.querySelector('input[name="token"]').setAttribute('value', result.token.id);
	  form.submit();
	  
	} else if (result.error) {
	  errorElement.textContent = result.error.message;
	  errorElement.classList.add('visible');
	}
  }
  
  cardNumberElement.on('change', function(event) {
	setOutcome(event);
  });
  
  document.querySelector('form').addEventListener('submit', function(e) {
	e.preventDefault();
	stripe.createToken(cardNumberElement).then(setOutcome);
  });
  
</script>
</html>