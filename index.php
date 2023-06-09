<?php
define('TOKEN', '*');
define('BASE_URL', 'https://api.telegram.org/bot' . TOKEN . '/');

$data = json_decode(file_get_contents('php://input'), TRUE);

$chat_id = $data['message']['chat']['id'];
$callback_query = $data['callback_query'];
$callback_id = $callback_query['message']['chat']['id'];
$callback_message_id = $callback_query['message']['message_id'];

$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
file_put_contents(__DIR__ . '/input.txt', print_r($data, 1)."\n", FILE_APPEND);

$callback_data = $data['data'];

$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');

$user = $data['from'];

$chatCollector = '-100';

switch ($message) {
    case '/start':
        $method = 'sendMessage';
        file_get_contents(BASE_URL . $method . '?chat_id=' . $chatCollector . '&text=@' . $user['username'] . ' ' . $user['first_name'] . ' ' . $user['last_name'] . ' ' . $user['id']);
        $method = 'sendPhoto';
        $send_data = [
            'chat_id' => $chat_id,
            'photo' => 'https://mywebsite.com/bot/img/img.jpg',
            'caption' => "Здравствуйте, " . $user['first_name'] . " " . $user['last_name'] . "!\n\nЭто Бот!",
            'parse_mode' => "html",
			'reply_markup' => [
                'resize_keyboard' => true,
                'keyboard' => [
                    [
                        ['text' => 'Меню'],
                    ],
                    [
                        ['text' => 'Контакты'],
                        ['text' => 'События'],
                    ],
                    [
                        ['text' => 'Пресс релиз'],
                        ['text' => 'Картинка'],
                        ['text' => 'Видео'],
                    ],
                    [
                        ['text' => 'Квиз'],
                    ]
                ]
            ]
        ];
        break;
	
	case '/menu':
	case 'меню':
        $method = 'sendMessage';
        $send_data = [
            'chat_id' => $chat_id,
            'text' => "Это меню.\n\nИспользуйте кнопки.",
            'reply_markup' => [
                'resize_keyboard' => true,
                'keyboard' => [
                    [
                        ['text' => 'О выставке'],
                    ],
                    [
                        ['text' => 'Художник — Ксения Воскобойникова'],
                    ],
                    [
                        ['text' => 'Начать осмотр'],
                    ]
                ]
            ]
        ];
        break;
	
	case '/contacts':
    case 'контакты':
		$method = 'sendMessage';
        $send_data = [
            'chat_id' => $chat_id,
            'text' => "Адрес\nМетро\nУлица\nЧасы работы\n\n+7 999 999-99-99\n\nmail@mail.ru",
			'parse_mode' => "html",
			'reply_markup' => [
                'inline_keyboard' => [
                    [
                        [
                            'text' => 'Телеграм',
                            'url' => 'https://t.me/chanel'
                        ],
						[
                            'text' => 'Сайт',
                            'url' => 'https://mywebsite.com/'
                        ],
						[
                            'text' => 'VK',
                            'url' => 'https://vk.com/group'
                        ]
                    ]
                ]
			]	
        ];
		break;
		
	case '/events':
    case 'события':
		$method = 'sendMessage';
        $send_data = [
            'chat_id' => $chat_id,
            'text' => "<a href='https://mywebsite.com/events'>События</a>",
			'parse_mode' => "html",
        ];
		break;

    case 'пресс релиз':
        $method = 'sendDocument';
        $send_data = [
	        'chat_id' => $chat_id,
            'document' => 'https://mywebsite.com/bot/doc/doc.pdf'
        ];
        break;

    case 'картинка':
		$method = 'sendPhoto';
        $send_data = [
            'chat_id' => $chat_id,
            'photo' => 'https://mywebsite.com/bot/img/img.jpg',
            'caption' => "«Картинка».",
            'parse_mode' => "html"
        ];
        break;
	
    case 'видео':
        $method = 'sendVideo';
        $send_data = [
            'chat_id' => $chat_id,
            'video' => 'https://mywebsite.com/bot/video/video.mov',
        ];
        break;

	case 'квиз':
    case 'вопросы':
        $method = 'sendMessage';
        $send_data = [
            'chat_id' => $chat_id,
			'text' => "Выберите квиз",
            'reply_markup' => [
                'resize_keyboard' => true,
                'keyboard' => [
                    [
                        ['text' => '№ 1'],
                        ['text' => '№ 2'],
                        ['text' => '№ 3'],
                    ],
                    [
                        ['text' => '№ 3'],
                        ['text' => '№ 1'],
                        ['text' => '№ 2'],
                    ],
                ]
            ]
        ];
        break;
    
    case '№ 1':
        $method = 'sendPhoto';
        $send_data = [
			'chat_id' => $chat_id,
            'photo' => 'https://mywebsite.com/bot/img/blue.jpg',
            'caption' => "Это синий?",			
			'reply_markup' => [
                'inline_keyboard' => [
                    [
                        [
                            'text' => 'Да',
                            'callback_data' => 'md'
                        ],
					],
					[
                        [
                            'text' => 'Нет',
                            'callback_data' => 'dm'
                        ],
                    ],
                ],
            ]
		];
        break;

    case '№ 2':
        $method = 'sendPhoto';
        $send_data = [
			'chat_id' => $chat_id,
            'photo' => 'https://mywebsite.com/bot/img/red.jpg',
            'caption' => "Это красный?",			
			'reply_markup' => [
                'inline_keyboard' => [
                    [
                        [
                            'text' => 'Нет',
                            'callback_data' => 'md2'
                        ],
					],
					[
                        [
                            'text' => 'Да',
                            'callback_data' => 'dm2'
                        ],
                    ],
                ],
            ]
		];
        break;

    case '№ 3':
        $method = 'sendPhoto';
        $send_data = [
			'chat_id' => $chat_id,
            'photo' => 'https://mywebsite.com/bot/img/yellow.jpg',
            'caption' => "<b><i>Это жёлтый?</i></b>",			
			'reply_markup' => [
                'inline_keyboard' => [
                    [
                        [
                            'text' => 'Да',
                            'callback_data' => 'md3'
                        ],
					],
					[
                        [
                            'text' => 'Нет',
                            'callback_data' => 'dm3'
                        ],
                    ],
                ],
            ]
		];
        break;

    case (preg_match("/^[a-zA-Z0-9_\-.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9]+$/", $message) ? true : false):
        $method = 'sendMessage';
        file_get_contents(BASE_URL . $method . '?chat_id=' . $chatCollector . '&text=@' . $user['username'] . ' ' . $user['first_name'] . ' ' . $user['last_name'] . ' ' . $user['id'] . ' ' . urlencode($message));
        $send_data = [
            'chat_id' => $chat_id,
            'text' => "Спасибо за почту. Будем на связи. " . $message
        ];
        break;

    case (preg_match("/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/", $message) ? true : false):
        $method = 'sendMessage';
        file_get_contents(BASE_URL . $method . '?chat_id=' . $chatCollector . '&text=@' . $user['username'] . ' ' . $user['first_name'] . ' ' . $user['last_name'] . ' ' . $user['id'] . ' ' . urlencode($message));
        $send_data = [
            'chat_id' => $chat_id,
            'text' => "Спасибо за телефон. Будем на связи. " . $message
        ];
        break;
	
	default:
        $method = 'sendMessage';
        $send_data = [
            'chat_id' => $chat_id,
            'text' => 'Я пока не умею обрабатывать такие команды :('
        ];
};

$removeButtonsMethod = $removeButtonsData = null;

switch($callback_data){
    case 'md':
        $method = 'sendMessage';
        $send_data = [
            'chat_id' => $callback_id,
            'text' => "Правильно!",
            'parse_mode' => 'html',
        ];
        $removeButtonsMethod = 'editMessageReplyMarkup';
        $removeButtonsData = [
            'chat_id' => $callback_id,
            'message_id' => $callback_message_id
        ];
        break;
	
	case 'dm':
        $method = 'sendMessage';
        $send_data = [
            'chat_id' => $callback_id,
            'text' => "Вы ошиблись...",
            'parse_mode' => 'html',
        ];
        $removeButtonsMethod = 'editMessageReplyMarkup';
        $removeButtonsData = [
            'chat_id' => $callback_id,
            'message_id' => $callback_message_id
        ];
        break;
	
    case 'md2':
        $method = 'sendMessage';
        $send_data = [
            'chat_id' => $callback_id,
            'text' => "Почти!",
            'parse_mode' => 'html',
        ];
        $removeButtonsMethod = 'editMessageReplyMarkup';
        $removeButtonsData = [
            'chat_id' => $callback_id,
            'message_id' => $callback_message_id
        ];
        break;
    
    case 'dm2':
        $method = 'sendMessage';
        $send_data = [
            'chat_id' => $callback_id,
            'text' => "Верно!",
            'parse_mode' => 'html',
        ];
        $removeButtonsMethod = 'editMessageReplyMarkup';
        $removeButtonsData = [
            'chat_id' => $callback_id,
            'message_id' => $callback_message_id
        ];
        break;

    case 'md3':
        $method = 'sendMessage';
        $send_data = [
            'chat_id' => $callback_id,
            'text' => "Все так.",
            'parse_mode' => 'html',
        ];
        $removeButtonsMethod = 'editMessageReplyMarkup';
        $removeButtonsData = [
            'chat_id' => $callback_id,
            'message_id' => $callback_message_id
        ];
        break;
    
    case 'dm3':
        $method = 'sendMessage';
        $send_data = [
            'chat_id' => $callback_id,
            'text' => "Мимо.",
            'parse_mode' => 'html',
        ];
        $removeButtonsMethod = 'editMessageReplyMarkup';
        $removeButtonsData = [
            'chat_id' => $callback_id,
            'message_id' => $callback_message_id
        ];
        break;
};

if ($removeButtonsMethod && $removeButtonsData) {
    sendTelegram($removeButtonsMethod, $removeButtonsData);
};

$res = sendTelegram($method, $send_data);

function sendTelegram($method, $data, $headers = []) {
    file_put_contents(__DIR__ . '/data.txt', print_r($data, 1)."\n", FILE_APPEND);
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => BASE_URL . $method,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"), $headers)
    ]);
    $result = curl_exec($curl);
    curl_close($curl);
    return (json_decode($result, 1) ? json_decode($result, 1) : $result);
};