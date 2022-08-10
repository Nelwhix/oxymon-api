<h2>Hello! </h2>
<p>
    The API has the following routes:
    <ul>
        <li>/api/login</li>
        <li>/api/register</li>
        <li>/api/apply</li>
        <li>/api/user</li>
    </ul>

<p>My approach was to give loans based on user's credit rating from A to F. For showcase
reasons, users are allowed to say their own credit ratings; I guessed that in a practical
scenario there will be a systematic method to give each user a rating based on bank statements etc.
Register route has required fields:
    <ul>'name', 'email', 'credit rating', 'password', 'password_confirmation'</ul>.
/apply route has required fields 
<ul>
<li>'principal'</li>
<li>'loan_duration' only takes values in years and months; but month
    values should be divided by 12</li>
<li>'interest_type' takes only two values 'simple' and 'compound'.</li></ul>

<p>If you are using postman, click <a href='https://www.postman.com/product/api-client/'>here</a></p>
