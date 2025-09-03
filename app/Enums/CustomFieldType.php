<?php

namespace App\Enums;

enum CustomFieldType: string
{
  case TEXT = 'text';
  case TEXTAREA = 'textarea';
  case SELECT = 'select';
  case RADIO = 'radio';
  case CHECKBOX = 'checkbox';
  case FILE = 'file';
  case EMAIL = 'email';
  case PHONE = 'phone';
  case NUMBER = 'number';
  case DATE = 'date';
}
