<?php


/**
 * Валидация формы ГЕТ для строки
 * @param string $param ключ параметра запроса
 * @param string|null $default Значение по умолчанию
 * @return string|null Возвращает параметр запроса в формате строки
 */
function request_retriveGetString(string $param, ?string $default): ?string
{
    {
        $result = ($_GET[$param] ?? $default);
        if (is_string($result)) {
            return (string)$result;
        }

        return $default;
    }
}

/**
 * Обеспечиваем безопасность от sql инъекций для чисел
 * @param string $param Ключ массива $_GET
 * @param int|null $default Значение по умолчанию
 * @return int Cтрого возвращает число
 */
function request_retriveGetInt(string $param, ?int $default): ?int
{
    $result = ($_GET[$param] ?? $default);
    if (is_numeric($result)) {
        return (int)$result;
    }

    return $default;
}
