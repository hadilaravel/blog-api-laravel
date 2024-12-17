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

//    public Const PermissionRole = 'PermissionRole';


    public static array $permissions = [
//        self::PermissionRole,
    ];

    public function getNamePerssionAttribute()
    {
        switch ($this->name)
        {
            case 'PermissionRole':
                return 'دسترسی به سطوح دسترسی';
                break;
            case 'PermissionAbout':
                return 'دسترسی به درباره ما';
                break;
            case 'PermissionCategoryProduct':
                return 'دسترسی به دسته بندی محصول';
                break;
            case 'PermissionBrand':
                return 'دسترسی به برند ها';
                break;
            case 'PermissionProduct':
                return 'دسترسی به محصولات';
                break;
            case 'PermissionStoreRoom':
                return 'دسترسی به انبار';
                break;
            case 'PermissionDelivery':
                return 'دسترسی به روش های ارسال';
                break;
            case 'PermissionPayment':
                return 'دسترسی به پرداخت ها';
                break;
            case 'PermissionOrder':
                return 'دسترسی به سفارشات';
                break;
            case 'PermissionCommentProduct':
                return 'دسترسی به نظرات محصول';
                break;
            case 'PermissionCustomer':
                return 'دسترسی به مشتریان';
                break;
            case 'PermissionUserAdmin':
                return 'دسترسی به کاربران ادمین';
                break;
            case 'PermissionDiscount':
                return 'دسترسی به تخفیفات';
                break;
            case 'PermissionCategoryPost':
                return 'دسترسی به دسته بندی پست ها';
                break;
            case 'PermissionPost':
                return 'دسترسی به پست ها';
                break;
            case 'PermissionLabel':
                return 'دسترسی به برچسب ها';
                break;
            case 'PermissionFaq':
                return 'دسترسی به سوالات متداول ';
                break;
            case 'PermissionCommentPost':
                return 'دسترسی به نظرات پست ها';
                break;
            case 'PermissionNotify':
                return 'دسترسی به  اطلاع رسانی';
                break;
            case 'PermissionCategoryTicket':
                return 'دسترسی به دسته بندی تیکت  ها';
                break;
            case 'PermissionTicket':
                return 'دسترسی به   تیکت ها';
                break;
            case 'PermissionSetting':
                return 'دسترسی به  تنظیمات';
                break;
        }
    }


}
