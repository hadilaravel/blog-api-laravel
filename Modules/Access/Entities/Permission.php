<?php

namespace Modules\Access\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name' ];

    public function roles ()
    {
        return $this->belongsToMany(Role::class );
    }

    public Const PermissionCategory = 'PermissionCategory';
    public Const PermissionPost = 'PermissionPost';
    public Const PermissionCustomer = 'PermissionCustomer';
    public Const PermissionUserAdmin = 'PermissionUserAdmin';
    public Const PermissionRole = 'PermissionRole';
    public Const PermissionComment = 'PermissionComment';
    public Const PermissionSetting = 'PermissionSetting';
    public Const PermissionSmsSetting = 'PermissionSmsSetting';


    public static array $permissions = [
        self::PermissionRole,
        self::PermissionUserAdmin,
        self::PermissionCustomer,
        self::PermissionPost,
        self::PermissionCategory,
        self::PermissionComment,
        self::PermissionSetting,
        self::PermissionSmsSetting,
    ];

    public function getNamePerssionAttribute()
    {
        switch ($this->name)
        {
            case 'PermissionCategory':
                return 'دسترسی به دسته بندی';
                break;
            case 'PermissionPost':
                return 'دسترسی به پست ها';
                break;
            case 'PermissionCustomer':
                return 'دسترسی به مشتریان';
                break;
            case 'PermissionUserAdmin':
                return 'دسترسی کاربران ادمین';
                break;
            case 'PermissionRole':
                return 'دسترسی به نقش ها';
                break;
            case 'PermissionComment':
                return 'دسترسی به نطرات';
                break;
            case 'PermissionSetting':
                return 'دسترسی به تنظیمات';
                break;
            case 'PermissionSmsSetting':
                return 'دسترسی به تنظیمات ارسال پیامک';
                break;
        }
    }


}
