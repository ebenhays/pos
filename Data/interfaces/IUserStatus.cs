using PointOfSale.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PointOfSale.Data.interfaces
{
	interface IUserStatus
	{
		IEnumerable<UserStatus> GetStatuses();
		void AddUserStatus(UserStatus status);
		UserStatus GetUserStatus(int id);
		void UpdateCategory(UserStatus status);
		void DeleteUserStatus(int id);
	}
}
