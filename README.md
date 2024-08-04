# Secure Messaging Application

This app allows you to share encrypted messages with colleagues, providing a safe and efficient way to send and receive encrypted messages with customizable expiry options.

## Key Features

-   **End-to-End Encryption**: All messages are encrypted to ensure maximum security.
-   **Multiple Recipients**: Send secure messages to one or more recipients.
-   **Expiry Options**:
    -   "Read Once": Messages are automatically deleted after being read.
    -   "Time-Based": Set a specific time for the message to expire.
-   **User-Friendly API**: Easy integration with other applications.
-   **Multi-Language Support**: Available in English and Dutch.

## Installation

Using Docker:

```
cp .env.example .env
docker-compose up -d
```

Application will be up running at http://localhost:8082

## Manual Installation

1. Clone the repository:

    ```
    git clone https://github.com/daniel-hbk/secure-messaging-app.git
    cd secure-messaging-app
    ```

2. Update .env.example PostgreSQL database connection settings

3. Run the app:

    ```
    bash bash/deploy.sh
    ```

## API Documentation

### Create a Message

**Endpoint:** `POST /api/v1/messages`

**Request Header:**

```json
{
    "Accept-Language": "nl"
}
```

**Request Body:**

```json
{
    "content": "This is a secret message",
    "recipients": ["recipient1@example.com", "recipient2@example.com"],
    "expiry_type": "read_once",
    "expiry_time": "2024-12-31T23:59:59Z"
}
```

**Response:**

```json
{
    "message": "Message created successfully",
    "identifier": "550e8400-e29b-41d4-a716-446655440000",
    "decryption_key": "randomgeneratedkey"
}
```

### Retrieve a Message

**Endpoint:** `GET /api/v1/messages/{identifier}`

**Query Parameters:**

-   `recipient_identifier`: Email of the recipient
-   `decryption_key`: Key provided when the message was created
-   `lang`: Language code of the message.

**Response:**

```json
{
    "content": "Decrypted message content",
    "created_at": "2024-12-15T10:30:00Z",
    "expiry_type": "read_once",
    "expiry_time": null
}
```

### Get Recipient Information

**Endpoint:** `GET /api/v1/recipients/{identifier}`

**Query Parameters:**

-   `lang`: Language code of the message.

**Response:**

```json
{
    "identifier": "recipient@example.com",
    "name": "John Doe"
}
```

## Security

Our application implements several security measures to protect your data:

-   **Message Key Encryption**: We use AES-256-CBC encryption for all messages.
-   **Decryption Key Handling**: Decryption keys are never stored in plain text. We only store a hash of the key.
-   **Message Expiry**: Messages are automatically deleted based on the specified expiry type.
-   **UUID for Messages**: Each message is assigned a unique UUID, making it virtually impossible to guess message identifiers.
-   **Rate Limiting**: API endpoints are protected against abuse through rate limiting.
-   **Input Validation**: All input is validated to prevent injection attacks.
-   **HTTPS Enforcement**: All API communications are encrypted in transit using HTTPS.

## Basic Usage

1. **Sending a Message**:

    - Use the Create Message API endpoint
    - Provide the message content, recipient(s), and expiry option
    - Store the returned message identifier and decryption key securely

2. **Retrieving a Message**:

    - Use the Retrieve Message API endpoint
    - Provide the message identifier, recipient identifier, and decryption key
    - The decrypted message content will be returned if all provided information is correct

3. **Checking Recipient Information**:

    - Use the Get Recipient Information API endpoint
    - Provide the recipient's identifier (usually an email address)
    - Recipient details will be returned if found

## Testing

Run the test suite with:

Docker:

```
docker-compose exec php php artisan test
```

Manual Env:

```
php artisan test
```
