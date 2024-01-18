require 'jwt'

key_file = '/var/www/html/key.txt'
team_id = '5Z25ZHHFU8'
client_id = 'com.traveldemo.tour.sid'
key_id = '7UCRZZFK62'

ecdsa_key = OpenSSL::PKey::EC.new IO.read key_file

headers = {
'kid' => key_id
}

claims = {
    'iss' => team_id,
    'iat' => Time.now.to_i,
    'exp' => Time.now.to_i + 86400*180,
    'aud' => 'https://appleid.apple.com',
    'sub' => client_id,
}

token = JWT.encode claims, ecdsa_key, 'ES256', headers

puts token
