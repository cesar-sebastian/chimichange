# ChimiChange API REST

## Deploy

<a href="SETUP.md">Setup</a>

## Endpoints

### Create Account

<pre><code class="http">POST /accounts
{
    &quot;email&quot;: &quot;test-client@chimichange.com&quot;,
    &quot;password&quot;: &quot;test-client&quot;
}
</code></pre>

### Authentication JWT

<pre><code class="http">http request 
POST /authentication_token

{
    "email": "test-client@chimichange.com",
    "password": "test-client"
}
</code></pre>

#### Deposit cash

<pre><code class="http">http request
POST /deposit
Bearer token
{
    "amount": 7000.00
}
</code></pre>

#### Exchange

<pre><code class="http">http request
POST /exchange
Bearer token
{
    "currencyFrom": "ARS",
    "currencyTo": "USD",
    "amount": 10000.00
}
</code></pre>

#### Resume Account

<pre><code class="http">http request
GET /accounts?page=1&user.email=test-client@chimichange.com
Bearer token
</code></pre>
